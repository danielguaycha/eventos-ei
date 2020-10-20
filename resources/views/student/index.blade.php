@extends('layouts.admin')
@push('nav')
    @include('notify-min')
@endpush
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
                           placeholder="Búsqueda por nombres y cedula">
                    <select name="type" id="type" class="form-select select-min">
                        <option value="" @if(!request()->query('type')) selected @endif >Búsqueda por...</option>
                        <option value="1" @if(request()->query('type') && request()->query('type') == 1) selected @endif>Nombres, Cedula</option>
                        <option value="2" @if(request()->query('type') && request()->query('type') == 2) selected @endif>Evento</option>
                    </select>
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
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Cédula</th>
                        <th>Tipo</th>
                        <th>Correo</th>
                        <th class="text-right"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($users) > 0)
                        @foreach($users as $u)
                            <tr>
                                <td data-name="Nombres">{{$u->surname}} {{ $u->name }} </td>
                                <td data-name="Cédula">{{ $u->dni }}</td>
                                <td data-name="Tipo">
                                    @if ($u->type === 'student')
                                        Estudiante
                                    @else
                                        Particular
                                    @endif
                                </td>
                                <td data-name="Correo">{{ $u->email }}</td>
                                <td class="text-right">
                                    @can('students.view.events')
                                        <a href="{{route('students.show', ['student' => $u->id])}}" title="Ver cursos"
                                           class="btn btn-sm btn-secondary"><i class="fa fa-graduation-cap"></i></a>
                                    @endcan

                                    @can('students.update')
                                        <a href="{{ route('students.edit', ['student' => $u->id]) }}"
                                           class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
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
                            <td colspan="5" class="no-data">
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
@push('js')
    <script>
        (function (){
            const val = document.getElementById('type').value;
            setPlaceHolder(val);
        })();

        document.getElementById("type").addEventListener('change', function (e) {
            const val = e.target.value;
            if (val) {
                setPlaceHolder(val);
            }
        });

        function setPlaceHolder(val) {
            let txtSearch = document.getElementById("q");
            switch (parseInt(val)) {
                case 1:
                    txtSearch.placeholder = 'Búsqueda por nombres y cedula';
                    break;
                case 2:
                    txtSearch.placeholder = 'Búsqueda por nombre de evento';
                    break;
            }
        }
    </script>
@endpush
