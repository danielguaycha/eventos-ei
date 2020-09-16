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
    }

    public function index(Request $request)
    {
        if ($request->user()->hasRole(User::rolRoot)) {
            $e = Event::with('sponsor')->get();
        }
        else {
            $e = $request->user()->events()->with('sponsor')->get();
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

        $e->save();

        $e->signatures()->sync($request->get('signatures'));
        $e->admins()->sync([$request->user()->id]);

        DocDesigns::create([
            'sponsor' => $sponsor->name,
            'description' => $this->getDescriptionByType($e),
            'event_id'=> $e->id,
            'date' => $e->f_fin,
            'sponsor_logo' => $sponsor->logo
        ]);

        DB::commit();

        return back()->with('ok', 'Evento creado con éxito');
    }

    private function getShortLink(){
        $rand = Str::random(8);
        if (Event::where('slug', $rand)->exists()) {
            return $this->getShortLink();
        }
        return $rand;
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

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }


    // listar postulantes
    public function listPostulantes($eventId) {

    }

    // function postular
    public function postular(Request $request, $event){

        $e = Event::findOrFail($event);

        if ($this->isPostulant($event, $request->user()->id)) {
            return back()->with('err', 'Ya has enviado tu inscripción para este evento');
        }

        EventPostulant::create([
           'event_id' => $e->id,
           'user_id' => $request->user()->id
        ]);

        return back()->with('ok', 'Tu inscripción fué enviada con éxito');
    }

    public function isPostulant($eventId, $userId) {
        return EventPostulant::where([
            ['event_id', $eventId],
            ['user_id', $userId]
        ])->exists();
    }


    // function for describe event
    private function getDescriptionByType(Event $e) {
        $desc= "";
        switch ($e->type){
            case Event::TypeAsistencia:
                $desc = "Por haber <b>ASISTIDO</b> al <b>$e->title</b> realizado ".$e->eventDateForDoc();
                break;
            case Event::TypeAprovacion:
                $desc = "Por haber <b>APROBADO</b> al <b>$e->title</b> obteniendo un promedio de {nota}";
                break;
            case Event::TypeAsistenciaAprovation:
                $desc = "Por haber <b>ASISTIDO</b> y <b>APROBADO</b> al <b>$e->title</b> realizado ".$e->eventDateForDoc().", con un promedio de {nota}";
                break;
        }
        if ($e->hours > 0) {
            $desc.= " equivalente a ".$e->hours." horas.";
        }
        return $desc;
    }
}
