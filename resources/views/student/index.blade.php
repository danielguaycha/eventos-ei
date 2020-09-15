@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0">
            <div class="title">
                <b>Listado de estudiantes</b>
                @can('students.store')
                    <a href="{{ route('students.create') }}"
                       title="Agregar nuevo administrador"
                       data-toggle="tooltip"
                       class="btn btn-link text-secondary"><i class="fa fa-user-plus"></i></a>
                @endcan
            </div>
            <div class="actions">
                {{--search--}}
                <form>
                    <input type="search" name="q" id="q"
                           value="{{ old('q', app('request')->input('q')) }}"
                           class="form-control"
                           placeholder="Buscar por: CI, Apellidos">
                    <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                    @if (app('request')->input("q"))
                        <a href="{{ route('students.index') }}"
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
                        <th>Tipo</th>
                        <th>Correo</th>
                        <th class="text-right">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($users) > 0)
                        @foreach($users as $u)
                            <tr>
                                <td>{{$u->surname}} {{ $u->name }} </td>
                                <td>{{ $u->dni }}</td>
                                <td>
                                    @if ($u->type === 'student')
                                        Estudiante
                                    @else
                                        Particular
                                    @endif
                                </td>
                                <td>{{ $u->email }}</td>
                                <td class="text-right">
                                    @can('students.update')
                                        <a href="{{ route('students.edit', ['student' => $u->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                                    @endcan

                                    @can('students.destroy')
                                        @include('admins._partials._btn_delete',
                                            ['id' => $u->id, 'route'=> route('students.destroy', ['student' => $u->id])])
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="5">
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

        @include('admins._partials._modal', ['msg'=> '¿Esta seguro que desea eliminar este estudiante?'])

        @if ($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
