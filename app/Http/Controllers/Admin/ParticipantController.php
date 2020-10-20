<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\EventParticipant;
use App\EventPostulant;
use App\Http\Controllers\Controller;
use App\Jobs\OneMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:events.participantes.index')->only(['index', 'list']);
        $this->middleware('permission:events.participantes.destroy')->only(['destroy']);
        $this->middleware('permission:events.participantes.add')->only(['add']);
        $this->middleware('permission:events.notas')->only(['listForNotas', 'calificar', 'saveNotas', 'confirmNotas']);
        $this->middleware('permission:events.notas_edit')->only(['editNotas']);
        $this->middleware('permission:events.sendmail')->only(['sendOneEmail']);
    }

    public function add(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id'
        ]);

        $exists = EventParticipant::where([
            ['event_id', $request->event_id],
            ['user_id', $request->user_id]
        ])->exists();

        if ($exists) {
            return response()->json([
                'ok' => false,
                'message' => 'Este estudiante ya fue agregado a la lista'
            ], 400);
        }

        $e = EventParticipant::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id
        ]);

        return response()->json([
            'ok'=> true,
            'body' => $e,
            'message' => 'El estudiante fué agregado con éxito',
        ]);
    }

    public function index($event){
        $e = Event::findOrFail($event);
        return view('events.participantes.index', ['event' => $e]);
    }

    public function list($event) {
        $p = EventParticipant::join('users', 'users.id', 'event_participants.user_id')
            ->join('persons', 'persons.id', 'users.person_id')
            ->select(
                'event_participants.*',
                'persons.dni',
                'persons.name', 'persons.surname',
                'users.id as user_id',
                'users.email'
            )
            ->where('event_participants.event_id', $event)
            ->orderBy('persons.surname', 'asc')
            ->paginate(50);

        return response()->json([
            'ok' => true,
            'body' => $p
        ], 200);
    }

    public function destroy($id){
        $e = EventParticipant::findOrFail($id);
        $postulante = EventPostulant::where([
            ['event_id', $e->event_id],
            ['user_id' , $e->user_id]
        ])->first();
        if ($postulante) {
            $postulante->update([
                'status' => EventPostulant::STATUS_PENDIENTE
            ]);
        }
        $e->delete();
        return response()->json([
            'ok' => true,
            'message' => 'Estudiante eliminado con éxito'
        ]);
    }

    // calificaciones
    public function calificar($event, Request $request){
        $e = Event::findOrFail($event);
        if ($e->type === Event::TypeAsistencia) {
            return redirect(route('events.index'))->with('info', 'El evento es de tipo asistencia');
        }

        if (!$request->user()->can('events.all')) {
            if (!$e->isAdmin($request->user()->id)) {
                abort(403);
            }
        }

        return view('events.notas.index', ['event' => $e]);
    }

    public function editNotas($event, Request $request){

        $e = Event::findOrFail($event);
        if ($e->type === Event::TypeAsistencia) {
            return redirect(route('events.index'))->with('info', 'El evento es de tipo asistencia');
        }

        if (!$request->user()->can('events.all')) {
            if (!$e->isAdmin($request->user()->id)) {
                abort(403);
            }
        }

        return view('events.notas.edit', ['event' => $e]);
    }

    public function listForNotas($event){
        $p = EventParticipant::join('users', 'users.id', 'event_participants.user_id')
            ->join('persons', 'persons.id', 'users.person_id')
            ->select(
                'event_participants.*',
                'persons.dni',
                'persons.name', 'persons.surname',
                'users.id as user_id',
                'users.email'
            )
            ->where('event_participants.event_id', $event)
            ->orderBy('persons.surname', 'asc')
            ->paginate(50);

        return response()->json([
            'ok' => true,
            'body' => $p
        ], 200);
    }

    // guardar calificaciones
    public function saveNotas(Request $request, $event) {
        $request->validate([
            'notas' => 'required|array',
            'notas.*.id' => 'required|exists:event_participants,id',
            'notas.*.nota_7' => 'required|numeric|min:0|max:7',
            'notas.*.nota_3' => 'required|numeric|min:0|max:3'
        ]);

        $e = Event::findOrFail($event);
        if ($e->status !== Event::STATUS_ACTIVO && !$request->user()->can('events.notas.edit')) {
            return response()->json([
                'ok' => false,
                'message' => 'Las calificaciones para este evento ya fueron procesadas'
            ], 400);
        }


        DB::beginTransaction();
        foreach ($request->notas as $n) {
            EventParticipant::find($n['id'])->update([
                'nota_7' => $n['nota_7'],
                'nota_3' => $n['nota_3'],
                'status' => EventParticipant::STATUS_CALIFICADO
            ]);
        }
        DB::commit();

        return response()->json([
            'ok' => true,
            'message'=> 'Notas guardadas con éxito'
        ]);
    }

    public function confirmNotas($event, Request $request)
    {
        $e = Event::withCount('participantes')->findOrFail($event);

        if ($e->participantes_count<=0) {
            return response()->json([
                'ok' => false,
                'message' => 'No hay participantes en este evento'
            ], 400);
        }

        if ($e->status !== Event::STATUS_ACTIVO && !$request->user()->can('events.notas.edit')) {
            return response()->json([
                'ok' => false,
                'message' => 'Las calificaciones para este evento ya fueron procesadas'
            ], 400);
        }
        DB::beginTransaction();

        EventParticipant::where("event_id", $e->id)->update([
           'status' => EventParticipant::STATUS_FINALIZADO
        ]);

        $e->update([
           'status' => Event::STATUS_CALIFICADO
        ]);

        DB::commit();

        return response()->json([
            'ok' => true,
            'message' => 'Calificaciones procesadas con éxito'
        ]);
    }

    public function aprobados($event) {

        $e = Event::findOrFail($event);

        if ($e->type === Event::TypeAsistencia) {
            $a = EventParticipant::where([
                'event_id' => $event
            ])->select(
                'id',
                'status'
            )->get();
            return response()->json([
                'ok' => true,
                'body' => $a
            ]);
        }


        $a = EventParticipant::where([
            'event_id' => $event
        ])->select(
            'id',
            'status'
        )->whereRaw('(nota_3 + nota_7) >= 7')->get();

        return response()->json([
            'ok' => true,
            'body' => $a
        ]);
    }

    // enviar certificado a estudiante
    public function sendOneEmail($participanteId){
        $participante = EventParticipant::findOrFail($participanteId);
        $event = $participante->event()->with('signatures')->first();

        if ($event->status === Event::STATUS_ACTIVO) {
            return response()->json([
               'ok' => false,
               'message' => 'Aun no se han enviado las notas para este evento'
            ], 400);
        }

        if ($event->type !== Event::TypeAsistencia) {
            $notaTotal = $participante->nota_7 + $participante->nota_3;
            if ($notaTotal < 7) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Este estudiante esta reprobado por nota'
                ], 400);
            }
        }

        $user = User::with('person')->findOrFail($participante->user_id);

        OneMail::dispatchAfterResponse($event, $user, $participante);

        return response()->json([
           'ok' => true,
           'message' => 'Certificado enviado correctamente'
        ]);
    }


}
