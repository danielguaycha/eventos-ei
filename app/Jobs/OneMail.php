<?php

namespace App\Jobs;

use App\Event;
use App\EventParticipant;
use App\EventPostulant;
use App\Mail\CertificateMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OneMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $event;
    protected $participante;
    public function __construct(Event $event, $user, $participante)
    {
        $this->event = $event;
        $this->user = $user;
        $this->participante = $participante;
    }

    public function handle()
    {
        Mail::to($this->user)->send(new CertificateMail($this->event, $this->user, $this->participante));

        if (!Mail::failures()) {
            $this->participante->update([
               'status' => EventParticipant::STATUS_ENVIADO
            ]);
        }
    }
}
