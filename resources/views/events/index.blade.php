@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0">
            <div class="title">
                <div>
                    <b>Listado de Eventos</b>
                    @include('_globals._helper', ['title'=> '¿Qué son los eventos?', 'content' => 'Son los cursos dictados u organizados por una persona determinada'])
                </div>
                @can('events.store')
                    <a href="{{ route('events.create') }}"
                       title="Agregar nueva evento"
                       data-toggle="tooltip"
                       class="btn btn-link text-secondary"><i class="fa fa-plus"></i></a>
                @endcan
            </div>
            <div class="actions">
                {{--search--}}
                <form>
                    <input type="search" name="q" id="q"
                           value="{{ old('q', app('request')->input('q')) }}"
                           class="form-control"
                           placeholder="Buscar por: Nombres">
                    <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                    @if (app('request')->input("q"))
                        <a href="{{ route('events.index') }}"
                           title="Limpiar búsqueda"
                           class="btn btn-danger"><i class="fa fa-times"></i></a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            @if (count($events)<=0)
                <div class="alert alert-info m-3" role="alert">
                    No se han agregado eventos
                </div>
            @endif
                <div class="ei-content">
                    @foreach ($events as $e)
                        <div class="ei-event-content">
                            <div class="ei-event">
                                <div class="ei-event-head">
                                    @if (Str::length($e->title) >= 40)
                                        <div class="title">
                                            <span>{{  Str::substr($e->title, 0, 40).'...'  }}
                                             <a href="#" role="button"tabindex="0"class="popover-dismiss"
                                                data-content="{{ $e->title }}"
                                                data-toggle="popover" data-trigger="focus" >Ver más</a>
                                            </span>
                                        </div>
                                    @else
                                        <div class="title">
                                            <span>
                                                {{ $e->title }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ei-event-body">
                                    <div class="organizador">
                                        @if ($e->sponsor->logo)
                                            <img src="{{ url('img/'.$e->sponsor->logo) }}" alt="logo">
                                        @endif
                                        <span>
                                            {{ $e->sponsor->name }}
                                        </span>
                                    </div>
                                    <div class="type">
                                        @switch($e->type)
                                            @case('asistencia')
                                            Asistencia
                                            @break
                                            @case('aprobacion')
                                            Aprobación
                                            @break
                                            @case('asistencia_aprobacion')
                                            Asistencia y Aprobación
                                            @break
                                        @endswitch
                                        <span><div>{{$e->hours}}h</div></span>
                                    </div>
                                    <a class="more" data-toggle="collapse" href="#dates_{{$e->id}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        <i class="fa fa-chevron-down"></i>
                                    </a>
                                    <div class="collapse" id="dates_{{$e->id}}">
                                        <div class="dates">
                                            <small>F. Matriculas</small>
                                            <div>{{ $e->matriculaDate() }}</div>
                                            <small>Periodo del curso</small>
                                            <div>{{ $e->eventDate() }}</div>
                                        </div>
                                    </div>

                                </div>
                                <div class="ei-event-footer">
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

                                        {{--<a href="#"><i class="fa fa-trash fa-lg"></i></a>
                                        <a href="#"><i class="fa fa-edit fa-lg"></i></a>--}}
                                            <a href="{{ route('postulates.index', ['event' => $e->id]) }}"
                                                class="btn"><i class="fa fa-users"></i></a>
                                        @can('events.design.view')
                                            <a class="btn btn-sm text-grey" href="{{ route('design.preview', ['eventId' => $e->id]) }}" data-toggle="tooltip"
                                              target="_blank"
                                               title="Ver diseño de certificado"><i class="fa fa-certificate fa-lg"></i></a>
                                        @endcan
                                        @can('events.design.edit')
                                            <a class="btn btn-sm"
                                                href="{{ route('doc.edit', ['id' => $e->id]) }}" data-toggle="tooltip"
                                               title="Diseñar certificado"><i class="fa fa-pen-nib fa-lg"></i></a>
                                        @endcan
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-sm" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v mx-1"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li><button class="dropdown-item" onclick="copyLink('{{  route('redirect.event', ['shortLink' => $e->short_link])  }}')">Copiar enlace</button></li>
                                                <li><a  class="dropdown-item" href="{{ route('events.show', ['event' => $e->slug]) }}" target="_blank">Ver evento</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function copyLink(url) {
            let aux = document.createElement("input");
            aux.setAttribute("value",url);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
        }
    </script>
@endpush
