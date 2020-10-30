@component('vendor.mail.html.message')
    <b>Mensaje: </b>
    <div>
        {{ $message }}
    </div>
    <br>
    <b>Nombre: </b> {{ $name }}<br>
    <b>Correo: </b> <a href="mailto:{{$from}}">{{$from}}</a>
@endcomponent
