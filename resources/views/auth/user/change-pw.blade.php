@extends('layouts.admin')
@push('nav')
    @include('notify-min')
@endpush
@section('content')
    <form class="card" method="post" action="{{ route('user.update_password') }}">
        @csrf
        @method('put')
        <div class="card-header">
            <b>Cambiar clave</b>
        </div>
        <div class="card-body">

            <div class="form-group">
                <label for="password_now">Contraseña Actual</label>
                <input type="password" class="form-control" name="password_now" id="password_now" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña Nueva</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>


            <div class="form-group">
                <label for="password_confirmation">Repite contraseña</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                       required>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-key mr-1"></i>Cambiar clave</button>
        </div>
    </form>
@stop
