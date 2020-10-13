@extends('layouts.admin')
@push('nav')
    @include('notify-min')
@endpush
@section('content')

    <form class="card" method="post" action="{{ route('rol.update', ['rol' => $role->id]) }}">
        @method('put')
        @csrf
        <div class="card-header">
            <b><i class="fa fa-user-lock"></i>Editar Rol | {{ $role->description }}</b>
        </div>
        <div class="card-body">
            <div class="form-group row">
                @if ($role->name !== \App\User::rolStudent && $role->name !== \App\User::rolAdmin)
                    <div class="col-sm-12 col-md-4">
                        <label for="name">Nombre del Rol</label>
                        <input type="text" value="{{ old('name', $role->description) }}"
                               class="form-control" name="name" id="name" placeholder="Use un nombre corto">
                    </div>
                @else
                    <input type="hidden" name="name" value="{{ $role->name }}">
                @endif
                <div class="col-sm-12 {{ $role->name !== \App\User::rolStudent && $role->name !== \App\User::rolAdmin ? 'col-md-8' : ''}}">
                    <label for="">Permisos: </label>
                    @include('rols._perms.perms',  ['perms' => $perms, 'selected' => $role->permissions])
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-success" type="submit"><i class="fa fa-save mr-2"></i>Actualizar Rol</button>
        </div>
    </form>
@stop
