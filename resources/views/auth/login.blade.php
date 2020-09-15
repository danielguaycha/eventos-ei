@extends('layouts.app')

@section('content')
<div class="container d-flex">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header py-3">
                    <b class="text-primary">INICIAR SESIÓN</b>
                </div>
                <div class="card-body px-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Correo: </label><input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña: </label><input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    Recordar mi sesión
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                Iniciar
                            </button>
                        </div>
                        <div class="form-group text-center">
                            <div style="font-family: sans-serif; color: #75757591" class="my-4">
                                &mdash;&mdash;&mdash;&mdash; ó &mdash;&mdash;&mdash;&mdash;
                            </div>
                            <a href="{{ route('register') }}" type="button" class="btn btn-block btn-outline-dark">Registrarse</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col">
                <div class="form-group text-center">
                    @if (Route::has('password.request'))
                        <a class="btn btn-link text-secondary" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
            </div>
        </div>


    </div>


</div>
@endsection
