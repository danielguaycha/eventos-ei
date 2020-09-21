@extends('layouts.admin')

@section('content')

        <participantes event="{{ $event->id }}"
                       @can('events.participantes.destroy') :can-delete="true" @endcan
                       @can('events.participantes.add') :add="true" @endcan />

@endsection
