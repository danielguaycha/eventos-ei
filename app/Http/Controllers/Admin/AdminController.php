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
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware("role:root");
    }

    public function index(Request $request)
    {
        $search = '';

        if ($request->query('q')) {
            $search = $request->query('q');
        }

        $admins = User::role(User::rolAdmin)->join("persons", 'persons.id', 'users.person_id')
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
        return view('admins.create');
    }

    public function store(AdminRequest $request)
    {
        DB::beginTransaction();
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
        $user->syncRoles(User::rolAdmin);

        if ($request->get('sendEmail'))
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

        if ($request->get('sendEmail') && $request->has('password'))
            $user->notify(new SendTempPassword($request->password));

        return back()->with('ok', 'Usuario modificado con éxito');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->role = User::rolStudent;
        $user->save();

        return back()->with('ok', 'El usuario fué dado de baja con éxito');
    }
}