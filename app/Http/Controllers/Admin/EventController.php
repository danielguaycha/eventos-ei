<?php

namespace App\Http\Controllers\Admin;

use App\DocDesigns;
use App\Event;
use App\EventPostulant;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Signature;
use App\Sponsor;
use App\User;
use App\UserAdminEvents;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
        // perms
        $this->middleware('permission:events.store')->only(['store', 'create']);
        $this->middleware('permission:events.index')->only(['index']);
        $this->middleware('permission:events.update')->only(['edit', 'update']);
        $this->middleware('permission:events.admins.add|events.admins.destroy')->only(['indexAdmins']);
    }

    public function index(Request $request)
    {
        $search = $request->query('q');
        if (!$search) $search = '';

        if ($request->user()->can('events.all')) {
            $e = Event::with('sponsor')
                ->withCount('postulants', 'participantes')
                ->where('title', 'like', "%$search%")
                ->orderBy('id', 'desc')
                ->get();
        }
        else {
            $e = $request->user()->events()
                ->with('sponsor')
                ->withCount('postulants', 'participantes')
                ->where('title', 'like', "%$search%")
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('events.index', [
            'events'=> $e
        ]);
    }

    public function create()
    {
        $sponsor = Sponsor::where('status', 1)->get();
        $signatures = Signature::where('status', 1)->get();
        return view('events.create', [
            'sponsors' => $sponsor,'signatures' => $signatures
        ]);
    }

    public function store(EventRequest $request)
    {
        DB::beginTransaction();

        $sponsor = Sponsor::find($request->sponsor_id);

        $e = new Event();
        // titulo y slug
        $e->title = Str::upper($request->title);
        $e->slug = Str::slug($e->title);
        $e->short_link = $this->getShortLink();

        $e->description = $request->description;
        $e->type = $request->type;
        $e->sponsor_id = $request->sponsor_id;
        $e->f_inicio = $request->f_inicio;
        $e->f_fin = $request->f_fin;
        $e->matricula_inicio = $request->matricula_inicio;
        $e->matricula_fin = $request->matricula_fin;
        $e->hours = $request->hours;

        if ($request->get('type') === Event::TypeAsistencia) {
            $e->status = Event::STATUS_CALIFICADO;
        }

        $e->save();

        $e->signatures()->sync($request->get('signatures'));
        $e->admins()->sync([$request->user()->id]);

        $imgSignatures = Signature::whereIn('id',$request->get('signatures'))->get();

        DocDesigns::create([
            'sponsor' => $sponsor->name,
            'description' => $this->getDescriptionByType($e),
            'event_id'=> $e->id,
            'date' => $e->f_fin,
            'sponsor_logo' => $sponsor->logo,
            'signatures' => $imgSignatures
        ]);

        DB::commit();

        return back()->with('ok', 'Evento creado con éxito');
    }

    private function getShortLink()
    {
        $rand = Str::random(8);
        if (Event::where('slug', $rand)->exists()) {
            return $this->getShortLink();
        }
        return $rand;
    }

    private function getDescriptionByType(Event $e)
    {
        $desc = "";
        switch ($e->type) {
            case Event::TypeAsistencia:
                $desc = "Por haber <b>ASISTIDO</b> al evento <b>$e->title</b> realizado " . $e->eventDateForDoc();
                if ($e->hours > 0) {
                    $desc .= " equivalente a " . $e->hours . " horas.";
                }
                break;
            case Event::TypeAprovacion:
                $desc = "Por haber <b>APROBADO</b> al evento <b>$e->title</b>";
                if ($e->hours > 0) {
                    $desc .= " equivalente a " . $e->hours . " horas,";
                }
                $desc .= "  obteniendo un promedio de {nota}";
                break;
            case Event::TypeAsistenciaAprovation:
                $desc = "Por haber <b>ASISTIDO</b> y <b>APROBADO</b> el evento <b>$e->title</b> realizado " . $e->eventDateForDoc();
                if ($e->hours > 0) {
                    $desc .= " equivalente a " . $e->hours . " horas,";
                }
                $desc .= " obteniendo un promedio de {nota}";
                break;
        }

        return $desc;
    }

    public function show($id)
    {
        $e = Event::with('sponsor')->where("slug", $id)->orWhere('id', $id)->first();
        if (!$e) {
            abort(404);
        }
        $user = Auth::user();
        $isPostulant = false;
        if ($user) {
            $isPostulant = $this->isPostulant($e->id, $user->id);
        }
        return view('events.guest.show', ['event' => $e, 'isPostulant' => $isPostulant]);
    }

    public function isPostulant($eventId, $userId)
    {
        return EventPostulant::where([
            ['event_id', $eventId],
            ['user_id', $userId]
        ])->exists();
    }

    public function edit($id)
    {
        $e = Event::with('signatures')->findOrFail($id);
        $sponsor = Sponsor::where('status', 1)->get();
        $signatures = Signature::where('status', 1)->get();
        return view('events.edit', [
            'event' => $e,
            'sponsors' => $sponsor,
            'signatures' => $signatures
        ]);
    }


    //* administradores de eventos

    public function update(EventRequest $request, $id)
    {
        DB::beginTransaction();
        $e = Event::findOrFail($id);
        $sponsor = Sponsor::find($request->sponsor_id);
        // titulo y slug
        $e->title = Str::upper($request->title);
        $e->slug = Str::slug($e->title);
        $e->short_link = $this->getShortLink();

        $e->description = $request->description;
        $e->type = $request->type;
        $e->sponsor_id = $request->sponsor_id;
        $e->f_inicio = $request->f_inicio;
        $e->f_fin = $request->f_fin;
        $e->matricula_inicio = $request->matricula_inicio;
        $e->matricula_fin = $request->matricula_fin;
        $e->hours = $request->hours;

        if ($request->get('type') === Event::TypeAsistencia) {
            $e->status = Event::STATUS_CALIFICADO;
        }
        $e->save();
        $e->signatures()->sync($request->get('signatures'));
        $imgSignatures = Signature::whereIn('id', $request->get('signatures'))->get();

        //diseño del certificado
        $msg = 'Datos del evento actualizados';
        if ($request->has('update_cert')) {
            DocDesigns::updateOrCreate(
                ['event_id' => $e->id],
                ['sponsor' => $sponsor->name,
                    'description' => $this->getDescriptionByType($e),
                    'event_id' => $e->id,
                    'date' => $e->f_fin,
                    'sponsor_logo' => $sponsor->logo,
                    'signatures' => $imgSignatures]);
            $msg = "Datos del evento y certificado actualizados";
        }

        DB::commit();
        return back()->with('ok', $msg);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return back()->with('ok', 'Evento eliminado con éxito');
    }

    public function indexAdmins($event){
        $e = Event::findOrFail($event);
        return view('events.admins.index', ['event' => $e]);
    }

    public function listAdmins($event) {

        $v = Event::with('admins')->find($event);
        $admins = $v->admins()
            ->with('person:id,name,surname,dni', 'roles:id,description')
            ->paginate(10);


        return response()->json([
            'ok' => true,
            'body' => $admins
        ]);
    }

    // function postular

    public function addAdmins(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $exists = UserAdminEvents::where([
            ['user_id', $request->user_id],
            ['event_id', $request->event_id]
        ])->exists();

        if ($exists) {
            return response()->json([
                'ok' => false,
                'message' => 'Esta persona ya se encuentra como administrador del evento'
            ], 400);
        }

        if (User::findOrFail($request->user_id)->hasRole(User::rolStudent)) {
            return response()->json([
                'ok' => false,
                'message' => 'No puedes asignar este evento a un estudiante'
            ], 400);
        }

        $e = UserAdminEvents::create([
            'user_id' => $request->user_id,
            'event_id' => $request->event_id
        ]);

        return response()->json([
           'ok' => true,
           'body' => $e,
           'message' => 'Administrador registrado con éxito'
        ]);
    }

    public function destroyAdmins($event, $user) {

        $admins = UserAdminEvents::where([
            ['user_id', $user],
            ['event_id', $event]
        ]);

        if (!$admins->exists()) {
            return response()->json([
                'ok' => false,
                'message' => 'No existe el administrador para este evento'
            ], 400);
        }

        $admins->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Administrador eliminando con éxito'
        ]);
    }


    // function for describe event

    public function postular(Request $request, $event)
    {

        $e = Event::findOrFail($event);

        if (!Carbon::now()->isAfter($e->matricula_fin)) {
            return back()->with('err', 'El periodo de matricula ha terminado');
        }

        if ($this->isPostulant($event, $request->user()->id)) {
            return back()->with('err', 'Ya has enviado tu inscripción para este evento');
        }

        EventPostulant::create([
           'event_id' => $e->id,
           'user_id' => $request->user()->id
        ]);

        return back()->with('ok', 'Tu inscripción fué enviada con éxito');
    }
}
