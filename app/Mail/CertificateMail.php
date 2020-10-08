<?php

namespace App\Mail;

use App\DocDesigns;
use App\Event;
use App\EventParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $event;
    protected $user;
    protected $participante;

    public function __construct(Event $event,  $user, EventParticipant $participante)
    {
        $this->event = $event;
        $this->user = $user;
        $this->participante = $participante;
    }

    public function build()
    {
        $design = DocDesigns::where('event_id', $this->event->id)->first();
        $pdf = \PDF::loadView('docs.certificate',
            [
                'event' => $this->event,
                'design'=> $design ,
                'user' => $this->user,
                'notas' => $this->participante
            ])
            ->setPaper('a4', 'landscape')->output();

        return $this
            ->subject("Certificado de ".$this->event->type())
            ->markdown('docs.emails.index', ['event' => $this->event, 'user' => $this->user])
            ->attachData($pdf, 'Certificado-'.$this->user->person->dni.'.pdf', [
                'mime' => 'text/pdf',
            ]);
    }
}
