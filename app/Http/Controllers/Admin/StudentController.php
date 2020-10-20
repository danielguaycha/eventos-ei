<?php

namespace App\Http\Controllers\Admin;

use App\EventParticipant;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Notifications\SendTempPassword;
use App\Person;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:students.index')->only(['index']);
        $this->middleware('permission:students.store')->only(['store', 'create']);
        $this->middleware('permission:students.update')->only(['edit', 'update']);
        $this->middleware('permission:students.destroy')->only(['destroy']);
        $this->middleware('permission:students.view.events')->only(['show']);
    }

    public function search(Request $request) {
        $search = '';
        $admins = false;
        if ($request->user()->hasRole(User::rolStudent)) {
            return response()->json([
                'ok' => false,
                'message' => 'No tienes permisos realizar esta acción'
            ]);
        }

        if ($request->query('search')) {
            $search = $request->query('search');
        }

        if ($request->query('admins')) {
            $admins = true;
        }

        $persons = User::join("persons", 'persons.id', 'users.person_id')
            ->select('persons.id as person_id','persons.name',
                'persons.surname', 'persons.dni', 'users.id', 'users.email')
            ->where([["status", ">", 0]])
            ->where(function ($query) use ($search){
                $query->orWhere("persons.dni", 'like', "$search%")
                    ->orWhere('persons.name', 'like', "%$search%")
                    ->orWhere('persons.surname', 'like', "%$search%");
            })
            ->orderBy('persons.created_at', 'desc')
            ->orderBy('persons.surname', 'asc');

        if ($admins) {
            $persons->whereHas('roles', function (Builder $query) {
                $query->where([
                    ['name', '<>', User::rolStudent],
                    ['name', '<>', User::rolRoot],
                ]);
            });
            $persons->with('roles:id,description');
        }

        return response()->json([
            'ok' => true,
            'body' => $persons->limit(5)->get()
        ]);
    }

    public function index(Request $request)
    {
        $search = '';
        $type = '';

        if ($request->query('q')) {
            $search = $request->query('q');
        }

        if ($request->query('type')) {
            $type = $request->query('type');
        }

        if ($type == 2) {
            $student = EventParticipant::join('users', 'users.id', 'event_participants.user_id')
                ->join('persons', 'persons.id', 'users.person_id')
                ->whereHas('event', function ($query) use($search){
                    return $query->where('title', 'like', "%$search%");
                })
                ->select(
                    'event_participants.status',
                    'users.id',
                    'users.email',
                    'persons.name',
                    'persons.surname',
                    'persons.dni'
                )
                ->paginate(30);
        }
        else  {
            $student = User::role(User::rolStudent)
                ->withCount('events_aprobados')
                ->join("persons", 'persons.id', 'users.person_id')
                ->select('persons.name', 'persons.surname', 'users.type', 'persons.dni', 'persons.id as person', 'users.email', 'users.id')
                ->where([["status", ">", 0]])
                ->where(function ($query) use ($search){
                    $query->orWhere("persons.dni", 'like', "$search%")
                        ->orWhere('persons.surname', 'like', "%$search%");
                })
                ->orderBy('users.id', 'desc')
                ->paginate(30);
            //return response()->json($student);
        }


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
        $e = User::with('person')->findOrFail($id);

        $eventos = EventParticipant::join('events', 'events.id', 'event_participants.event_id')
            ->where('event_participants.user_id', $e->id)
            ->select(
                'events.id',
                'events.title',
                'events.type',
                'event_participants.nota_7',
                'event_participants.nota_3',
                'event_participants.status'
            )
            ->get();

        return view('student.show', ['student' => $e, 'events' => $eventos]);
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

        if ($request->get('sendEmail') && $request->get('password'))
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
