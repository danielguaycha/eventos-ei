<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendCert extends Notification implements ShouldQueue
{
    use Queueable;
    protected $file;
    public function __construct($file)
    {
        $this->file = $file;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!')
                    ->attach($this->file, [
                        'as' => 'filename.pdf',
                        'mime' => 'text/pdf',
                    ]);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
