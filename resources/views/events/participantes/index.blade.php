@extends('layouts.admin')

@section('content')
    <div class="ei-card-head">
        <b>Evento: </b>{{ $event->title }}
    </div>
    <participantes :event="{{ $event }}"
                   @if (request()->query('sendmail') ) :mail="true" @endif
                   @can('events.sendmail') :can-send="true" @endcan
                   @can('events.participantes.destroy') :can-delete="true" @endcan
                   @can('events.participantes.add') :add="true" @endcan />
@endsection

@push('nav')
    <div class="ei-direct-access">
        @can('events.postulantes.index')
            <a href="{{ route('postulates.index', ['event' => $event->id]) }}"><i class="fa fa fa-users mr-1"></i>Postulantes</a>
        @endcan
        @if ($event->type !== \App\Event::TypeAsistencia)
            @can('events.notas')
                <a href="{{ route('events.notas', ['event' => $event->id]) }}"><i class="fa fa fa-clipboard-list mr-1"></i>Notas</a>
            @endcan
        @endif
    </div>
@endpush
