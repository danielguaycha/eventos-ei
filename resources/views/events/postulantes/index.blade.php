@extends('layouts.admin')

@section('content')
    <div class="ei-card-head">
        <b>Evento: </b>{{ $event->title }}
    </div>
    <postulantes
        @can('events.postulantes.accept') :can-accept="true" @endcan
        :event="{{ request()->event }}"></postulantes>
@stop

@push('nav')
    <div class="ei-direct-access">
        @can('events.participantes.index')
            <a href="{{ route('participantes.index', ['evento' => $event->id]) }}"><i class="fa fa fa-user-graduate mr-1"></i>Participantes</a>
        @endcan
        @if ($event->type !== \App\Event::TypeAsistencia)
            @can('events.notas')
                <a href="{{ route('events.notas', ['event' => $event->id]) }}"><i class="fa fa fa-clipboard-list mr-1"></i>Notas</a>
            @endcan
        @endif
    </div>
@endpush
