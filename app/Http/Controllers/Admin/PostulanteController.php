<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\EventPostulant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostulanteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('listar');
        //$this->middleware('auth')->only('listar');
    }

    public function index(){
        return view('events.postulantes.index');
    }

    public function listar($event) {

        $e = EventPostulant::join('users', 'users.id', 'event_postulant.user_id')
            ->join('persons', 'persons.id', 'users.person_id')
            ->select('persons.name', 'persons.surname', 'users.id as user')
            ->where('event_postulant.event_id', $event)
            ->paginate(50);

        return response()->json([
            'data' => $e,
            'ok' => true
        ], 200);
    }

}
