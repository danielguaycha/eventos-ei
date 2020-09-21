@extends('layouts.admin')

@section('content')
    @if ($event->status === \App\Event::STATUS_ACTIVO)
        <notas :event="{{ $event->id }}"/>
    @else
        <div class="card">
            <div class="card-header">
                <b><i class="fa fa-user"></i>Calificaciones</b>
            </div>
            <div class="card-body p-0">
                <table-notas :event="{{ $event->id }}"></table-notas>
            </div>
        </div>
    @endif
@stop
