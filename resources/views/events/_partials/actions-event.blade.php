<div class="btn-group">
    {{--Notas--}}
    @if ($e->type !== \App\Event::TypeAsistencia)
        @can('events.notas')
            <a href="{{ route('events.notas', ['event'=> $e->id]) }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-clipboard-list"></i> Notas</a>
        @endcan
    @endif
    @if ($e->participantes_count > 0 && $e->status !== \App\Event::STATUS_ACTIVO)
        {{--Enviar correos--}}
        <a href="{{ route('participantes.index', ['evento' => $e->id]) }}?sendmail=true"
           data-toggle="tooltip" title="Enviar certificados"
           class="btn btn-sm btn-outline-dark"><i class="fa fa-envelope"></i></a>
    @endif
</div>
<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    @can('events.participantes.index')
        {{---Participantes--}}
        <a href="{{ route('participantes.index', ['evento' => $e->id]) }}" class="btn btn-sm" data-toggle="tooltip" title="Participantes ({{$e->participantes_count}})">
            <i class="fa fa-user-graduate"></i> {{ $e->participantes_count }}
        </a>
    @endcan

    @can('events.destroy')
        {{--Eliminar eventos--}}
        <a class="btn btn-sm text-danger delete"
                data-id="{{ $e->id }}"
                title="Eliminar"
                data-url="{{ route('events.destroy', ['event' => $e->id]) }}"
                data-toggle="modal" data-target="#deleteConfirm">
            <i class="fa fa-trash"></i>
        </a>
        {{--Editar evento--}}
            <a href="{{ route('events.edit', ['event'=> $e->id]) }}"
               title="Editar"
               class="btn btn-sm text-success"><i class="fa fa-pencil-alt"></i></a>
    @endcan


    <div class="btn-group" role="group">
        {{--Más opciones--}}
        <button id="btnGroupDrop1" type="button" class="btn btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v mx-1"></i></button>
        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            @can('events.postulantes')
                {{--Postulantes--}}
                <li><a href="{{ route('postulates.index', ['event' => $e->id]) }}"
                       class="dropdown-item">
                        Postulantes <span class="badge bg-dark">{{$e->postulants_count}}</span>
                    </a></li>
            @endcan
            @can('events.admins.add|events.admins.destroy')
                {{--Administradores---}}
                <li><a  class="dropdown-item" href="{{ route('events.admins', ['event' => $e->id]) }}">Administradores</a></li>
            @endcan
            @can('events.design.view')
                <li><hr class="dropdown-divider"></li>
                {{--Ver diseño del certificado--}}
                <li><a class="dropdown-item" href="{{ route('design.preview', ['eventId' => $e->id]) }}"
                       target="_blank">Visualizar certificado</a></li>
            @endcan
            @can('events.design.edit')
                {{--Editar el certificado--}}
                <li>
                    <a class="dropdown-item"
                       href="{{ route('doc.edit', ['id' => $e->id]) }}">Diseñar certificado</a>
                </li>
            @endcan

                <li><hr class="dropdown-divider"></li>
            {{--Ver el curso--}}
            <li><a  class="dropdown-item" href="{{ route('events.show', ['event' => $e->slug]) }}" target="_blank">Ver evento</a></li>
            {{--Enlace para compartir--}}
            <li><button class="dropdown-item" onclick="copyLink('{{  route('redirect.event', ['shortLink' => $e->short_link])  }}')">Copiar enlace</button></li>
        </ul>
    </div>
</div>
