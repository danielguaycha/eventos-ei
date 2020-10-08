@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0">
                <div class="title">
                    <b>Listado de usuarios</b>
                    <a href="{{ route('admins.create') }}"
                       title="Agregar nuevo administrador"
                       data-toggle="tooltip"
                       class="btn btn-link text-secondary"><i class="fa fa-user-plus"></i></a>
                </div>
                <div class="actions">
                    {{--search--}}
                    <form>
                        <input type="search" name="q" id="q"
                               value="{{ old('q', app('request')->input('q')) }}"
                               class="form-control"
                               placeholder="Buscar por: CI, Apellidos, Nombres">
                        <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                        @if (app('request')->input("q"))
                            <a href="{{ route('admins.index') }}"
                               title="Limpiar búsqueda"
                               class="btn btn-danger"><i class="fa fa-times"></i></a>
                        @endif
                    </form>
                </div>
        </div>
        <div class="card-body p-0">
            @include('notify')
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Cédula</th>
                        <th>Rol</th>
                        <th>Correo</th>
                        <th class="text-right">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($users) > 0)
                        @foreach($users as $u)
                            <tr>
                                <td data-name="Nombres">{{$u->surname}} {{ $u->name }} </td>
                                <td data-name="dni">{{ $u->dni }}</td>
                                <td data-name="Rol">
                                    @if (count($u->roles) > 0)
                                        {{ $u->roles->pluck('description')[0] }}
                                    @endif

                                </td>
                                <td data-name="Correo">{{ $u->email }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admins.perms', ['userId' => $u->id]) }}" class="btn btn-sm btn-success"><i class="fa fa-cogs"></i></a>
                                    <a href="{{ route('admins.edit', ['admin' => $u->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                                    @include('admins._partials._btn_delete',
                                            ['id' => $u->id, 'route'=> route('admins.destroy', ['admin' => $u->id])])
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="4">
                                @if ( app('request')->input('q'))
                                    No existe información para tu búsqueda
                                @else
                                    No se han agregado usuarios aún
                                @endif
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        @include('admins._partials._modal', ['msg'=> '¿Esta seguro que desea eliminar este usuario?'])

        @if ($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
