@extends('layouts.admin')

@section('content')
    <form class="card" method="post" action="{{ route('admins.store') }}">
        @csrf
        <div class="card-header">
            <b><i class="fa fa-users-cog"></i> Crear usuarios</b>
        </div>
        <div class="card-body">  {{--form--}}
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="card-body-title">Datos básicos</div>
                    <div class="form-group">
                        <label for="dni">Cedula: </label>
                        <input type="text"
                               autofocus="autofocus"
                               required
                               value="{{ old('dni') }}"
                               class="form-control" name="dni" id="dni" placeholder="Ingrese cedula">
                    </div>
                    <div class="form-group">
                        <label for="surname">Apellidos: </label>
                        <input type="text"
                               required
                               value="{{ old('surname') }}"
                               class="form-control" name="surname" id="surname" placeholder="Ingrese apellidos">
                    </div>

                    <div class="form-group">
                        <label for="name">Nombres: </label>
                        <input type="text"
                               required
                               value="{{ old('name') }}"
                               class="form-control" name="name" id="name" placeholder="Ingrese nombres">
                    </div>


                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="card-body-title">Credenciales</div>
                    <div class="form-group">
                        <label for="email">Email: </label>
                        <input type="email"
                               required
                               value="{{ old('email') }}"
                               class="form-control" name="email" id="email" placeholder="Ingrese un correo">
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="row">
                            <div class="col flex-grow-1">
                                <input type="text"
                                       required
                                       class="form-control" name="password" id="password" aria-describedby="helpPw" placeholder="Ingrese contraseña">
                            </div>
                            <div class="col flex-grow-0">
                                <button type="button"
                                        data-toggle="tooltip"
                                        data-placement="left"
                                        title="Generar nueva contraseña"
                                        onclick="getPw()" class="btn btn-secondary"><i class="fa fa-redo-alt"></i></button>
                            </div>
                        </div>
                        <small id="helpPw" class="form-text text-muted">Debe incluir mínimo 8 caracteres, entre ellos signo, números mayúsculas, minúsculas</small>
                    </div>
                    <div class="form-check mt-2">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="sendEmail" id="sendEmail" @if (old('sendEmail')) checked @endif >
                            Enviar contraseña al correo
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col">
                    <div class="card-body-title">Seleccione un rol</div>
                    @foreach($roles as $r)
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="role" id="{{ $r->name }}" value="{{ $r->id  }}"
                                       @if(old('role') == $r->id  || $r->name === \App\User::rolAdmin) checked @endif> {{ $r->description }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">Crear Administrador</button>
        </div>
    </form>
@endsection
@push('nav')
    @include('notify-min')
@endpush

@section('js')
    <script>
        window.onload = function () {
            getPw()
        }
        function getPw() {
            document.getElementById('password').value = generatePassword(15);
        }
    </script>
@endsection
