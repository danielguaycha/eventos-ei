<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SendTempPassword extends Notification
{
    use Queueable;
    private $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Aviso de cuenta asignada')
                    ->line('Has recibido esta notificación porque estas registrado
                        en el sistema de la Escuela informática, usa el correo actual y la siguiente contraseña para iniciar sesión.')
                    ->line(new HtmlString('Contraseña: <b>'.$this->password.'</b>'))
                    ->action('Iniciar sesión', url(route('login')))
                    ->line('Te recomendamos que hagas el cambio de contraseña desde tu perfil de usuario, una vez accedas al sistema.');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
