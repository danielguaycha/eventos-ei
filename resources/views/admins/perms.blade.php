@extends('layouts.admin')

@section('content')
    <form class="card" action="{{ route('admins.perms_save', ['userId' => $user->id]) }}" method="post">
        @csrf
        @method('post')
        <div class="card-header">
            <div>
                <b>({{ count($selected) }}) Permisos</b> | <small>{{ $user->person->surname }} {{ $user->person->name }}</small>
            </div>
            <div>
                <button class="btn btn-primary btn-sm"><i class="fa fa-save mr-1"></i>Guardar</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row m-0">
                <div class="col-sm-12 p-0">
                    @include('rols._perms.perms',  ['perms' => $perms, 'selected' => $selected])
                </div>
            </div>
        </div>
    </form>
@endsection
@push('nav')
    @include('notify-min')
@endpush
