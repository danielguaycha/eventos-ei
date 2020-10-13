@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/js/fileinput.css') }}">
@endsection
@push('nav')
    @include('notify-min')
@endpush
@section('content')
    <form class="card" method="post" action="{{ route('sponsor.store') }}" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="card-header">
            <b>Nuevo organizador</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <div class="form-group">
                        <label for="name">Nombre: </label>
                        <input type="text"
                               value="{{ old('name') }}"
                               maxlength="100"
                               class="form-control" name="name" id="name" placeholder="Ingrese Nombre" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="img">Logo: </label>
                    <input id="img" name="image"
                           accept=".png, .jpg, .jpeg"
                           value="{{ old('image') }}"
                           type="file" class="file" data-browse-on-zone-click="true">
                </div>
            </div>

        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-signature"> </i> Guardar Organizador</button>
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
            previewFileType:'any',
            language: 'es',
            showCaption: false,
            removeClass: 'btn btn-danger',
            allowedFileExtensions: ["png"],
            minImageHeight: 50,
            zoomClass: 'btn btn-primary'});
    </script>
@endsection
