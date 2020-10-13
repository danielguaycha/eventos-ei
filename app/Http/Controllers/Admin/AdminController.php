<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Notifications\SendTempPassword;
use App\Person;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;


class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware("role:root")->except(['search']);
    }

    public function search(Request $request) {

    }

    public function index(Request $request)
    {
        $search = '';

        if ($request->query('q')) {
            $search = $request->query('q');
        }

        $admins = User::whereHas('roles', function (Builder $query) {
                $query->where([
                    ['name', '<>', User::rolStudent],
                    ['name', '<>', User::rolRoot],
                ]);
            })
            ->join("persons", 'persons.id', 'users.person_id')
            ->select('persons.name', 'persons.surname', 'persons.dni', 'users.*')
            ->where([["status", ">", 0]])
            ->where(function ($query) use ($search){
                $query->orWhere("persons.dni", 'like', "$search%")
                    ->orWhere('persons.surname', 'like', "%$search%");
            })
            ->paginate(30);


        return view('admins.index', ['users'=> $admins]);
    }

    public function create()
    {
        $roles = Role::where('name', '<>', User::rolRoot)
            ->where('name', '<>', User::rolStudent)
            ->get();
        return view('admins.create', ['roles' => $roles]);
    }

    public function store(AdminRequest $request)
    {
        DB::beginTransaction();
        $role = Role::findOrFail($request->role);

        $person = Person::create(
            [
                'dni' => $request->dni,
                'name' => Str::upper($request->name),
                'surname' => Str::upper($request->surname),
            ]
        );

        if ($person->user)
            if ($person->user->hasRole([User::rolAdmin, User::rolRoot])) {
                DB::rollBack();
                return back()->with("err", "Esta persona ya tiene un rol de administrador, puede editarlo")->withInput($request->all());
            }

        $user = User::create([
            'email' => Str::lower($request->email),
            'person_id' => $person->id,
            'password' => Hash::make($request->password),
            'type' => 'other'
        ]);

        $user->syncRoles([$role]);

        if ($request->get('sendEmail') && $request->password)
            $user->notify(new SendTempPassword($request->password));

        DB::commit();

        return back()->with("ok", 'Usuario '.$request->name.' creado con éxito');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $u = User::with('roles')->findOrFail($id);
        $roles = Role::where('name', '<>', User::rolRoot)->get();

        return view('admins.edit', ['user'=> $u, 'roles' => $roles]);
    }

    public function update(AdminRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $person = Person::findOrFail($user->person_id);
        $role = Role::findById($request->role);

        if ($role->name === User::rolRoot) {
            return back()->with('err', "El rol $role->name no es válido");
        }

        $existPerson = Person::where([
            ['dni', $request->dni],
            ['id', '<>', $person->id]
        ])->exists();

        if ($existPerson) {
            return back()->with("err", "Ya existe un usuario registrado con esta cédula");
        }

        $user->email = $request->email;

        $person->name = $request->name;
        $person->surname = $request->surname;
        $person->dni = $request->dni;

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('role')) {
            $user->syncRoles($role);
        }

        $person->save();
        $user->save();

        if ($request->get('sendEmail') && $request->get('password'))
            $user->notify(new SendTempPassword($request->password));

        return back()->with('ok', 'Usuario modificado con éxito');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->type = User::rolStudent;
        $user->syncRoles(User::rolStudent);
        $user->save();

        return back()->with('ok', 'El usuario fué dado de baja con éxito');
    }

    public function showPerms($userId) {
        $user = User::with('person')->findOrFail($userId);
        $perms = Permission::orderBy('module')->get();
        $permsSelected = $user->getDirectPermissions();
        return view('admins.perms', ['user' => $user, 'perms' => $perms, 'selected' => $permsSelected]);
    }

    public function savePerms(Request $request, $user){
        $request->validate([
            'perms' => 'array',
            'perms.*' => 'numeric|exists:permissions,id'
        ]);
        $u = User::findOrFail($user);
        $u->syncPermissions($request->get('perms'));

        return back()->with('ok', 'Permisos actualizados con éxito');
    }
}
