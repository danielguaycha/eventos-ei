@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/js/fileinput.css') }}">
@endsection
@push('nav')
    @include('notify-min')
@endpush
@section('content')
    <form class="card" method="post" action="{{ route('sponsor.update', ['sponsor' => $sponsor->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="card-header">
            <b>Editar Organizador</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Nombre: </label>
                        <input type="text"
                               value="{{ old('name', $sponsor->name) }}"
                               maxlength="100"
                               class="form-control" name="name" id="name" placeholder="Ingrese Nombre" required>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-center align-items-center flex-column">

                    @if ($sponsor->logo)
                        <b>Logo Actual: </b>
                        <img src="{{ url('img/'.$sponsor->logo) }}" alt="logo_" width="auto" height="150px">
                    @else
                        <small class="text-muted">No ha seleccionado un logo</small>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="img">Cambiar Logo: </label>
                    <input id="img" name="logo"
                           accept=".png, .jpg, .jpeg"
                           value="{{ old('image') }}"
                           type="file" class="file" data-browse-on-zone-click="true">
                </div>
            </div>

        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-signature"> </i> Actualizar Organizador</button>
        </div>
    </form>

@endsection
@section('js')
    <script src="{{ asset('plugins/dropzone/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('plugins/dropzone/js/locales/es.js') }}"></script>
    <script>
        $("#img").fileinput({
            showZoom: true,
            showUpload:false,
            previewFileType:'png',
            language: 'es',
            showCaption: false,
            removeClass: 'btn btn-danger',
            allowedFileExtensions: ["png"],
            minImageHeight: 50,
            maxFileSize: 0,
            zoomClass: 'btn btn-primary'});
    </script>
@endsection
