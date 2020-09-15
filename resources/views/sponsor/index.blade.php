@extends('layouts.admin')

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="title">
                <div>
                    <b>Listado de organizadores</b>
                    @include('_globals._helper', ['title' => '¿Qué son los organizadores?', 'content' => 'Son los entes que validan la entrega del certificado, es asignado al crear un nuevo evento, de alli su nombre, es quien lo organiza, se require un nombre y opcional un logo (puede aparecer en el certificado final)'])
                </div>

                <a href="{{ route('sponsor.create') }}"
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
                        <a href="{{ route('sponsor.index') }}"
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
                        <th>Nombre</th>
                        <th class="text-right">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($sponsors as $s)
                        <tr>
                            <td>
                                @if ($s->logo)
                                    <img src="{{ url('img/'.$s->logo).'/20' }}" alt="logo_" height="20px" class="mr-2">
                                @endif
                                {{ $s->name }}
                            </td>
                            <td class="text-right">
                                @if ($s->logo)
                                    <a href="{{ url('img/'.$s->logo) }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-image"></i></a>
                                @endif
                                <a href="{{ route('sponsor.edit', ['sponsor' => $s->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-pencil-alt"></i></a>

                                @include('admins._partials._btn_delete',
                                            ['id' => $s->id, 'route'=> route('sponsor.destroy', ['sponsor' => $s->id])])
                            </td>
                        </tr>
                    @endforeach
                    @if (count($sponsors) <= 0)
                        <tr>
                            <td colspan="3" class="text-center">No existen organizadores registrados</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if ($sponsors->hasPages())
            <div class="card-footer">
                {{ $sponsors->links() }}
            </div>
        @endif
    </div>
    @include('admins._partials._modal', ['msg'=> '¿Esta seguro que desea dar de baja este organizador?'])

@endsection
