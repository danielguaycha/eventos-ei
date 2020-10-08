@component('vendor.mail.html.message')
    <div>
        <h1>Hola, {{ $user->person->name }}</h1>
        <p>
            Felicidades por haber
            @switch($event->type)
                @case(\App\Event::TypeAsistencia)
                    <b>Asistido</b>
                @break
                @case(\App\Event::TypeAsistenciaAprovation)
                    <b>Asistido y Aprobado</b>
                @break
                @case(\App\Event::TypeAprovacion)
                    <b>Aprobado</b>
                @break
            @endswitch
            &nbsp;el evento {{ $event->title }}.
        </p>
        <p>A continuación adjuntamos tu respectivo certificado.</p>
        <br>
        Saludos, <br>
        Escuela informática
    </div>
@endcomponent

