@extends('layouts.admin')

@section('content')
    <form class="card" method="post" action="{{ route('events.update', ['event' => $event->id]) }}">
        @csrf
        @method('put')
        <div class="card-header">
            <b>Modificar evento</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Titulo del evento</label>
                        <textarea class="form-control" name="title" required id="title"
                                  placeholder="Ingrese un titulo para el evento"
                                  rows="2" maxlength="150">{{old('title', $event->title)}}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">Tipo de evento</label>
                        <select class="form-select" name="type" id="type" required>
                            <option value="">Seleccione...</option>
                            <option value="asistencia" @if (old('type', $event->type) === 'asistencia') selected @endif >Asistencia</option>
                            <option value="aprobacion" @if (old('type', $event->type) === 'aprobacion') selected @endif >Aprobación</option>
                            <option value="asistencia_aprobacion" @if (old('type', $event->type) === 'asistencia_aprobacion') selected @endif >Asistencia & Aprobación</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Organizador: {{ $event->sponsor_id }}</label>
                        <select class="form-select" name="sponsor_id" id="sponsor_id" required>
                            <option value="">Seleccione....</option>
                            @foreach($sponsors as $e)
                                <option value="{{ $e->id }}"
                                        @if(old('sponsor_id', $event->sponsor_id) == $e->id)
                                            selected
                                        @endif >{{$e->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hours">Horas: </label>
                        <input type="number" value="{{ old('hours', $event->hours) }}"
                               class="form-control" name="hours" id="hours" placeholder="Numero de horas">
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea type="text"
                                  class="form-control" name="description"
                                  id="description" placeholder="Descripción del evento" rows="6">{{old('description', $event->description)}}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="card-body-title">Fechas de Evento</div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="f_inicio">Fecha de inicio del evento</label>
                                <input type="date" value="{{ old('f_inicio', $event->f_inicio) }}" required
                                       class="form-control" name="f_inicio" id="f_inicio" placeholder="Ejem. d/m/a">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="f_fin">Fecha de fin del evento</label>
                                <input type="date" value="{{ old('f_fin', $event->f_fin) }}" required
                                       class="form-control" name="f_fin" id="f_fin" placeholder="Ejem. d/m/a">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="card-body-title">Fechas de Matricula</div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="matricula_inicio">Fecha de inicio de matriculas</label>
                                <input type="date" value="{{ old('matricula_inicio', $event->matricula_inicio) }}" required
                                       class="form-control" name="matricula_inicio" id="matricula_inicio" placeholder="Ejem. d/m/a">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="matricula_fin">Fecha limite de matricula</label>
                                <input type="date" value="{{ old('matricula_fin', $event->matricula_fin) }}" required
                                       class="form-control" name="matricula_fin" id="matricula_fin" placeholder="Ejem. d/m/a">
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card-body-title">Firmas</div>
                    <div class="form-group">
                        <select class="selectpicker" id="signatures" required
                                name="signatures[]" data-style="btn-normal" title="Escoja máximo 4"
                                data-live-search="true" data-max-options="4" data-size="10" multiple>
                            @foreach ($signatures as $s)
                                <option data-tokens="{{ $s->name }}"
                                        value="{{ $s->id }}"
                                        data-subtext="{{ $s->cargo }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


        </div>
        <div class="card-footer text-right">
            <button type="submit" name="update_cert" value="true"
                    class="btn btn-outline-primary"><i class="fa fa-redo-alt mr-1"></i>Actualizar evento & certificado
            </button>

            <button type="submit"
                    class="btn btn-success"><i class="fa fa-redo-alt mr-1"></i>Actualizar
            </button>
        </div>
    </form>
@stop

@push('nav')
    @include('notify-min')
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select/css/bootstrap-select.css') }}"/>
@endpush
@push('js')
    <script src="{{ asset('plugins/select/js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#signatures').selectpicker('val', @php echo $event->signatures->pluck('id') @endphp);
        });
    </script>
@endpush
