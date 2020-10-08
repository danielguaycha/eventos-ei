@extends('layouts.app')

@section('content')
    <div class="container d-flex">
       <div class="row justify-content-center align-items-center">
           <div class="col-md-5">
               <div class="card">
                   @include('notify')
                   <div class="card-body">
                       <h5 class="card-title">
                           {{ $event->title }}
                       </h5>
                       <h6 class="card-subtitle mb-2 text-muted">{{ $event->sponsor->name }}</h6>
                       @if ($event->description)
                           <p class="card-text">{{ $event->description }}</p>
                       @endif

                   </div>
                   <ul class="list-group list-group-flush">
                       @if ($event->hours > 0)
                           <li class="list-group-item"><span>Horas: </span> <b>{{ $event->hours }}</b></li>
                       @endif
                       <li class="list-group-item"><span>Fecha Inicio: </span> <b>{{ \Carbon\Carbon::parse($event->f_inicio)->isoFormat("D/MMM/YY") }}</b></li>
                       <li class="list-group-item"><span>Matricula: </span> <b>{{ $event->matriculaDate() }}</b></li>
                       <li class="list-group-item"><span>Tipo: </span> <b>{{ $event->type() }}</b></li>
                   </ul>
                   <div class="card-body">

                       @if (!$isPostulant)
                           @if (\Carbon\Carbon::now()->isBefore($event->matricula_fin))
                               <a href="{{ route('events.postular', ['event' => $event->id]) }}" class="btn btn-primary btn-block"> <i class="fa fa-graduation-cap mr-2"></i> Inscribirme</a>
                           @else
                                <span class="d-block text-center text-danger">El periodo de matricula finalizó</span>
                           @endif
                       @else
                           <a class="btn btn-success btn-block disabled"><i class="fa fa-check mr-2"></i>INSCRITO</a>
                       @endif
                   </div>
               </div>
           </div>
       </div>
    </div>
@endsection
