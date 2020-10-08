@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="title">
                <b>Roles</b>
                <a href="{{ route('rol.create') }}"
                   title="Agregar nuevo rol"
                   data-toggle="tooltip"
                   class="btn btn-link text-secondary"><i class="fa fa-plus"></i></a>
            </div>
        </div>
        <div class="card-body m-0 p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Rol</th>
                            <th class="text-right">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $r)
                            <tr>
                                <td data-name="Rol">{{ $r->description }}</td>
                                <td data-name="Opciones" class="text-right">
                                     @if($r->name !== \App\User::rolStudent && $r->name !== \App\User::rolAdmin)
                                        @include('admins._partials._btn_delete',
                                            ['id' => $r->id, 'route'=> route('rol.destroy', ['rol' => $r->id])])
                                     @endif
                                    <a href="{{ route('rol.edit', ['rol' => $r->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admins._partials._modal', ['msg'=> '¿Esta seguro que desea eliminar este Rol?'])
@endsection
@push('nav')
    @include('notify-min')
@endpush
