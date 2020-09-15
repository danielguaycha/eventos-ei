@extends('layouts.admin')

@section('content')
    <form class="card" method="post" action="{{ route('events.store') }}">
        @csrf
        @method('post')
        <div class="card-header">
            <b>Gestionar nuevo evento</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Titulo del evento</label>
                        <textarea class="form-control" name="title" required id="title" placeholder="Ingrese un titulo para el evento" rows="2" maxlength="150">{{old('title')}}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">Tipo de evento</label>
                        <select class="form-control" name="type" id="type" required>
                            <option value="">Seleccione...</option>
                            <option value="asistencia" @if (old('type') === 'asistencia') selected @endif >Asistencia</option>
                            <option value="aprobacion" @if (old('type') === 'aprobacion') selected @endif >Aprobación</option>
                            <option value="asistencia_aprobacion" @if (old('type') === 'asistencia_aprobacion') selected @endif >Asistencia & Aprobación</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Organizador: </label>
                        <select class="form-control" name="sponsor_id" id="sponsor_id" required>
                            <option value="">Seleccione....</option>
                            @foreach($sponsors as $e)
                                <option value="{{ $e->id }}" @if(old('sponsor_id') === $e->id) selected @endif >{{$e->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="hours">Horas: </label>
                      <input type="number"
                          class="form-control" name="hours" id="hours" placeholder="Numero de horas">
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea type="text"
                                  class="form-control" name="description"
                                  id="description" placeholder="Descripción del evento" rows="6">{{old('description')}}</textarea>
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
                                <input type="date" value="{{ old('f_inicio') }}" required
                                       class="form-control" name="f_inicio" id="f_inicio" placeholder="Ejem. d/m/a">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="f_fin">Fecha de fin del evento</label>
                                <input type="date" value="{{ old('f_fin') }}" required
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
                                <input type="date" value="{{ old('matricula_inicio') }}" required
                                       class="form-control" name="matricula_inicio" id="matricula_inicio" placeholder="Ejem. d/m/a">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="matricula_fin">Fecha limite de matricula</label>
                                <input type="date" value="{{ old('matricula_fin') }}" required
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

            @include('notify')
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">Guardar Evento</button>
        </div>
    </form>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/select/css/bootstrap-select.css') }}"/>
@endsection
@section('js')
    <script src="{{ asset('plugins/select/js/bootstrap-select.min.js') }}"></script>
    <script>
        $.fn.selectpicker.Constructor.DEFAULTS.multipleSeparator = ' | ';
    </script>
@endsection
