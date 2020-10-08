@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <b>Estudiante | {{ $student->person->surname }} {{ $student->person->name }}</b>
        </div>
        <div class="card-body p-0 m-0">
            <table class="table table-bordered m-0">
                <thead>
                <tr>
                    <th>Evento</th>
                    <td>Tipo</td>
                    <th class="text-center">Calificación</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($events as $e)
                        <tr>
                            <td data-name="Evento">
                                <div>
                                    {{ $e->title }}
                                </div>
                            </td>
                            <td data-name="Tipo">
                                @switch($e->type)
                                    @case(\App\Event::TypeAsistencia) Asistencia @break
                                    @case(\App\Event::TypeAsistenciaAprovation) Asistencia y Aprobación @break
                                    @case(\App\Event::TypeAprovacion) Aprobación @break
                                @endswitch
                            </td>
                            <td class="text-center" data-name="Calificación">
                                @if ($e->type !== \App\Event::TypeAsistencia)
                                    {{ $e->nota_7 + $e->nota_3 }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center" data-name="Estado">
                                @if ($e->type !== \App\Event::TypeAsistencia)
                                    @if ($e->nota_7 + $e->nota_3 < 7 )
                                        <b class="text-danger">Reprobado</b>
                                    @else
                                        <b class="text-success">Aprobado</b>
                                    @endif
                                @else
                                    <b class="text-success">Aprobado</b>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    @if (count($events)<=0)
                        <tr class="text-center"><td colspan="3">Este estudiante no registra eventos</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
