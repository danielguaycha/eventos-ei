@extends('layouts.admin')

@section('content')

    <div class="card">
        <div class="card-header">

                <div class="title">
                    <div>
                        <b>Listado de Firmas</b>
                        @include('_globals._helper', ['title'=> '¿Qué son las firmas?', 'content' => 'Son las rubricas de las personas que aparecerán al final del certificado y son asignadas al crear un evento o diseñar el mismo, se requiere el cargo de la persona y su nombre'])
                    </div>

                    <a href="{{ route('signatures.create') }}"
                       title="Agregar nueva firma"
                       data-toggle="tooltip"
                       class="btn btn-link text-secondary"><i class="fa fa-plus"></i></a>
                </div>
                <div class="actions">
                    {{--search--}}
                    <form>
                        <input type="search" name="q" id="q"
                               value="{{ old('q', app('request')->input('q')) }}"
                               class="form-control"
                               placeholder="Buscar por: Nombres">
                        <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                        @if (app('request')->input("q"))
                            <a href="{{ route('signatures.index') }}"
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
                        <th>Cargo</th>
                        <th>Nombre</th>
                        <th class="text-right">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($signatures as $s)
                        <tr>
                            <td>
                                {{ $s->cargo }}
                            </td>
                            <td>{{ $s->name }}</td>
                            <td class="text-right">
                                <a href="{{ url('img/'.$s->image) }}" target="_blank"
                                   data-toggle="tooltip" title="Ver firma de {{ $s->cargo }}"
                                   class="btn btn-sm btn-success"><i class="fa fa-image"></i></a>

                                <a href="{{ route('signatures.edit', ['signature' => $s->id]) }}"
                                   data-toggle="tooltip" title="Editar"
                                    class="btn btn-sm btn-primary">
                                    <i class="fa fa-pencil-alt"></i></a>

                                @include('admins._partials._btn_delete',
                                            ['id' => $s->id, 'route'=> route('signatures.destroy', ['signature' => $s->id])])
                            </td>
                        </tr>
                    @endforeach
                    @if (count($signatures) <= 0)
                        <tr>
                            <td colspan="3" class="text-center">No existen firmas adicionadas</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if ($signatures->hasPages())
            <div class="card-footer">
                {{ $signatures->links() }}
            </div>
        @endif
    </div>
    @include('admins._partials._modal', ['msg'=> '¿Esta seguro que desea dar de baja esta firma?'])

@endsection
