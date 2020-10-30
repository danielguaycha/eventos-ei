<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $name;
    public $topic;

    public function __construct($name, $topic, $message)
    {
        $this->topic = $topic;
        $this->message = $message;
        $this->name = $name;
    }

    public function build()
    {
        return $this->markdown('_globals.email.contact')
            ->subject($this->topic);
    }
}
