<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Person;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth',"root"]);
    }

    public function index(Request $request)
    {
        $search = '';

        if ($request->query('q')) {
            $search = $request->query('q');
        }

        $admins = User::join("persons", 'persons.id', 'users.person_id')
            ->select('persons.name', 'persons.surname', 'persons.dni', 'users.*')
            ->where([["status", ">", 0], ['role', User::rolAdmin]])
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
        $person = Person::updateOrCreate(
            ['dni' => $request->dni],
            [
                'name' => $request->name,
                'surname' => $request->surname,
            ]
        );

        if ($person->user)
            if ($person->user->role === User::rolAdmin) {
                DB::rollBack();
                return back()->with("err", "Esta persona ya tiene un rol de administrador, puede editarlo")->withInput($request->all());
            }

        User::create([
            'email' => $request->email,
            'person_id' => $person->id,
            'password' => Hash::make($request->password),
            'role' => User::rolAdmin
        ]);

        DB::commit();

        return back()->with("ok", 'Usuario '.$request->name.' creado con éxito');
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $u = User::findOrFail($id);

        return view('admins.edit', ['user'=> $u]);
    }


    public function update(AdminRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $person = Person::findOrFail($user->person_id);

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
            $user->role = $request->role;
        }

        $person->save();
        $user->save();

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
