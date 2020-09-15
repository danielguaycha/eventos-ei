<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Notifications\SendTempPassword;
use App\Person;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:students.index')->only(['index']);
        $this->middleware('permission:students.store')->only(['store', 'create']);
        $this->middleware('permission:students.update')->only(['edit', 'update']);
        $this->middleware('permission:students.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $search = '';

        if ($request->query('q')) {
            $search = $request->query('q');
        }

        $student = User::join("persons", 'persons.id', 'users.person_id')
            ->select('persons.name', 'persons.surname', 'users.type', 'persons.dni', 'persons.id as person', 'users.email', 'users.id')
            ->where([["status", ">", 0]])
            ->where(function ($query) use ($search){
                $query->orWhere("persons.dni", 'like', "$search%")
                    ->orWhere('persons.surname', 'like', "%$search%");
            })
            ->paginate(30);


        return view('student.index', ['users'=> $student]);
    }

    public function create()
    {
        return view('student.create');
    }

    public function store(StudentRequest $request)
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
            if ($person->user->hasRole([User::rolStudent])) {
                DB::rollBack();
                return back()->with("err", "Ya existe un estudiante registrado con está cedula")->withInput($request->all());
            }

        $user = User::create([
            'email' => Str::lower($request->email),
            'person_id' => $person->id,
            'password' => Hash::make($request->password),
            'type' => $request->type
        ]);
        $user->syncRoles(User::rolStudent);

        DB::commit();

        if ($request->get('sendEmail'))
            $user->notify(new SendTempPassword($request->password));

        return back()->with("ok", 'Estudiante '.$request->name.' creado con éxito');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $u = User::with('roles')->findOrFail($id);
        $roles = Role::where('name', '<>', User::rolRoot)->get();

        return view('student.edit', ['user'=> $u, 'roles' => $roles]);
    }

    public function update(StudentRequest $request, $id)
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

        $user->email = Str::lower($request->email);

        $person->name = Str::upper($request->name);
        $person->surname = Str::upper($request->surname);
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

        return back()->with('ok', 'Estudiante modificado con éxito');
    }

    public function destroy($id)
    {
        $e = User::findOrFail($id);
        $p = Person::findOrFail($e->person_id);

        $e->delete();
        $p->delete();
        return back()->with("ok", "Estudiante eliminado con éxito");
    }
}
