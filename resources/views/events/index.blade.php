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
                                @can('events.visibility')
                                    <div class="btn-visible">
                                        <event-visible :event="{{ $e->id }}" :visible="{{ $e->visible }}"/>
                                    </div>
                                @endcan
                                @if (Str::length($e->title) >= 90)
                                    <div class="title">
                                            <span>{{  Str::substr($e->title, 0, 90).'...'  }}
                                             <a href="#" role="button" tabindex="0" class="popover-dismiss"
                                                data-content="{{ $e->title }}"
                                                data-toggle="popover" data-trigger="focus">Ver más</a>
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
                                        <img src="{{ url('img/'.$e->sponsor->logo.'/20') }}" alt="logo">
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

                                </div>
                                <a class="more" data-toggle="collapse" href="#dates_{{$e->id}}" role="button"
                                   aria-expanded="false" aria-controls="collapseExample">
                                    <i class="fa fa-chevron-down"></i>
                                    <span>{{$e->hours}}h</span>
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
                            <div class="ei-event-footer d-flex justify-content-between">
                                @include('events._partials.actions-event', ['e' => $e])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @include('admins._partials._modal', ['msg'=> '¿Esta seguro que desea eliminar este evento?'])
@endsection
@push('nav')
    @include('notify-min')
@endpush
@push('js')
    <script>
        function copyLink(url) {
            let aux = document.createElement("input");
            aux.setAttribute("value",url);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            window.toast.success("Enlace copiado", {icon: 'feather'});
        }
    </script>
@endpush
