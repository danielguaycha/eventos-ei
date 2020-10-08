@extends('layouts.admin')

@section('content')
    <div class="ei-card-head">
        <b>Evento: </b>{{ $event->title }}
    </div>
    <notas :event="{{ $event->id }}"/>
@endsection
