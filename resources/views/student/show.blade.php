@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <b>Estudiante | {{ $student->person->surname }} {{ $student->person->name }}</b>
        </div>

        <div class="card-body p-0 m-0">
            <students
                @can('events.sendmail') :can-send="true" @endcan
            :events="{{ $events }}"/>
        </div>
    </div>
@endsection
