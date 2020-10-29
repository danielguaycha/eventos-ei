@extends('layouts.app')

@section('content')
    <div class="container d-flex">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="ei-verify">
                    <div class="head">

                        <a href="/">
                            <img src="{{ asset('img/ico/favicon-32x32.png') }}" alt="ei-logo">
                        </a>

                        <div>
                            <a href="{{ url('/') }}" class="mr-1">Home</a>
                        </div>
                    </div>
                    <div class="title">
                        <h4>{{ $event->title }}</h4>
                        <h5 class="card-subtitle mb-2">{{ $event->sponsor->name }}</h5>
                        @if ($event->description)
                            <p class="card-text">{{ $event->description }}</p>
                        @endif
                    </div>
                    <div class="body">
                        @include('notify')
                        <ul class="ei-event-list">
                            @if ($event->hours > 0)
                                <li><span>Horas: </span> <b>{{ $event->hours }}</b></li>
                            @endif
                            <li><span>Fecha Inicio: </span>
                                <b>{{ \Carbon\Carbon::parse($event->f_inicio)->isoFormat("D/MMM/Y") }}</b></li>
                            <li><span>Matricula: </span> <b>{{ $event->matriculaDate() }}</b>
                            </li>
                            <li><span>Tipo: </span> <b>{{ $event->type() }}</b></li>
                        </ul>
                    </div>
                    @if (!$isPostulant)
                        {{ \Carbon\Carbon::now() }}
                        @if (\Carbon\Carbon::now()->isBefore(\Carbon\Carbon::parse($event->matricula_fin)))
                            <a href="{{ route('events.postular', ['event' => $event->id]) }}"
                               class="btn btn-primary btn-block mt-2"> <i class="fa fa-graduation-cap mr-2"></i>
                                Inscribirme</a>
                        @else
                            <span class="d-block text-center text-danger mt-2">El periodo de matricula finalizó</span>
                        @endif
                    @else
                        @if (\Carbon\Carbon::now()->isBefore($event->f_fin))
                            <a class="btn btn-success btn-block disabled mt-2"><i class="fa fa-check mr-2"></i>INSCRITO</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
