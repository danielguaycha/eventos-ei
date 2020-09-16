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
        <div class="card-body m-0 py-0">
            <div class="table-responsive">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Rol</th>
                            <th class="text-right">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $r)
                            <tr>
                                <td>{{ $r->description }}</td>
                                <td class="text-right">
                                    <a href="{{ route('rol.edit', ['rol' => $r->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
