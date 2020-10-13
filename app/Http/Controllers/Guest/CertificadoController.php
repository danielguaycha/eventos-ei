<?php

namespace App\Http\Controllers\Guest;

use App\DocDesigns;
use App\EventParticipant;
use App\Http\Controllers\Controller;
use App\Libs\Hashids\Hashids;
use App\User;

class CertificadoController extends Controller
{
    public function __construct()
    {

    }

    public function show($id)
    {
        // obteniendo el id
        $hashids = new Hashids();

        //dd($hashids->encode(30));
        $id_decode = $hashids->decode($id);
        if (count($id_decode) <= 0) abort(400, 'Hash no encontrado');

        // buscando el participante y el evento
        $participant = EventParticipant::find($id_decode)->first();
        if (!$participant) abort(404, 'Estudiante no registrado en este evento');

        // buscando el usuario
        $user = User::with('person')->findOrFail($participant->user_id);

        // buscando el diseño del certificado
        $design = DocDesigns::where('event_id', $participant->event_id)->first();

        return \PDF::loadView('docs.certificate',
            [
                'design' => $design,
                'user' => $user,
                'notas' => $participant
            ])
            ->setPaper('a4', 'landscape')
            ->stream($user->person->surname . '-CERTIFICADO.pdf');
    }
}
