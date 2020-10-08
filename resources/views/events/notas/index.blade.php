@extends('layouts.admin')

@section('content')
    <div class="ei-card-head">
        <b>Evento: </b>{{ $event->title }}
    </div>
    @if ($event->status === \App\Event::STATUS_ACTIVO)
        <notas :event="{{ $event->id }}"/>
    @else
        <div class="card">
            <div class="card-header">
                <b><i class="fa fa-user"></i>Calificaciones</b>
                @can('events.notas.edit')
                    <div>
                        <a href="{{ route('events.notas_edit', ['event'=> $event->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit mr-1"></i>Editar notas</a>
                    </div>
                @endcan
            </div>
            <div class="card-body p-0">
                <table-notas :event="{{ $event->id }}"></table-notas>
            </div>
        </div>
    @endif
@stop

@push('nav')
    <div class="ei-direct-access">
        @can('events.participantes.index')
            <a href="{{ route('participantes.index', ['evento' => $event->id]) }}"><i class="fa fa fa-user-graduate mr-1"></i>Participantes</a>
        @endcan
    </div>
@endpush

