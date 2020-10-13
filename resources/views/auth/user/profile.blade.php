@extends('layouts.admin')

@section('content')
    <form class="card" action="" method="post">
        @csrf @method('put')
        <div class="card-header">
            <b>Perfil de usuario</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <label for="name">Nombre: </label>
                    <input type="text"
                           required value="{{ old('name', $user->person->name) }}"
                           class="form-control" name="name" id="name" placeholder="Ingrese nombre">
                </div>
                <div class="col-sm-12 col-md-6">
                    <label for="surname">Apellido: </label>
                    <input type="text" required
                           value="{{ old('surname', $user->person->surname) }}"
                           class="form-control" name="surname" id="surname" placeholder="Ingrese apellido">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="email">Email: </label>
                    <input type="text" required
                           value="{{ old('email', $user->email) }}"
                           class="form-control" name="email" id="email" placeholder="Ingrese correo">
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('user.password') }}" class="btn btn-primary"><i class="fa fa-key mr-1"></i>Cambiar
                clave</a>
            <button type="submit" class="btn btn-success"><i class="fa fa-redo mr-1"></i>Actualizar datos</button>
        </div>
    </form>
@stop
@push('nav')
    @include('notify-min')
@endpush
