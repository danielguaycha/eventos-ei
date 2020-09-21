@extends('layouts.admin')
@section('content')
    <admins
        @can('events.admins.destroy') :can-delete="true" @endcan
        @can('events.admins.add') :can-add="true" @endcan
        :event="{{ $event->id }}"></admins>
@stop
