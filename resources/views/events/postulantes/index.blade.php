@extends('layouts.admin')

@section('content')

    <postulantes
        @can('events.postulantes.accept') :can-accept="true" @endcan
        :event="{{ request()->event }}"></postulantes>
@stop
