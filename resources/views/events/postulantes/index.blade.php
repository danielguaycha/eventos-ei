@extends('layouts.admin')

@section('content')
    <postulante event="{{ request()->event }}"></postulante>
@stop
@push('js')

@endpush
