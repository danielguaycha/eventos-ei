@extends('layouts.admin')

@section('content')

    <form class="card" method="post" action="{{ route('rol.store') }}">
        @method('post')
        @csrf
        <div class="card-header">
            <b><i class="fa fa-user-lock"></i>Crear Rol</b>
        </div>
        <div class="card-body">
            @include('notify')
            <div class="form-group row">
                <div class="col-sm-12 col-md-4">
                    <label for="name">Nombre del Rol</label>
                    <input type="text" value="{{ old('name') }}"
                           class="form-control" name="name" id="name" placeholder="Use un nombre corto">
                </div>
                <div class="col-sm-12 col-md-8">
                        <label for="">Permisos: </label>
                        @include('rols._perms.perms',  ['perms' => $perms])
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-primary" type="submit"><i class="fa fa-save mr-2"></i>Guardar Rol</button>
        </div>
    </form>
@stop
