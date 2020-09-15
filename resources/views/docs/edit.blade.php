@extends('layouts.admin')

@section('content')
    <form class="card" method="post" action="{{ route('design.update', ['id' => $doc->id ]) }}">
        @method('put')
        @csrf
        <div class="card-header">
            <b>Diseñar certificado</b>
        </div>
        <div class="card-body">
            @include('notify')
            <div class="doc-design">
                <div class="head">
                    <img src="{{ asset('img/doc/head-min.png') }}" alt="Header" height="30px">
                    <span></span>
                </div>
                <div class="content">
                    <div class="encabezado">
                        <div class="logo logo_a">
                            <img src="{{ asset('img/utm-logo.png') }}" alt="utmach_logo">
                        </div>
                        <div class="membrete">
                            <h3>UNIVERSIDAD TÉCNICA DE MACHALA</h3>
                            <h4>D.I. NO. 69-04 DE 14 DE ABRIL DE 1969</h4>
                            <h5>
                                <i>&mdash;&mdash;&nbsp;</i>
                                {{ $doc->sponsor }}
                                <i>&nbsp;&mdash;&mdash;</i>
                            </h5>
                        </div>
                        <div class="select_logo">
                            <div class="select-logo-b">
                                <select name="sponsor_logo" id="sponsor_logo"
                                        class="form-control selectpicker" title="Seleccione Logo">
                                    <option value="">Ninguno....</option>
                                    @foreach ($sponsors as $e)
                                        <option @if ($e->logo === $doc->sponsor_logo) selected @endif
                                            data-content="<span><img class='logo_b' src='{{ url('/img/'.$e->logo) }}'/></span>"
                                                value="{{ $e->logo }}"></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="otorga my-2">
                        <div class="form-group w-75 m-auto">
                            <select class="form-control-plaintext select-center text-primary" name="otorga" id="otorga">
                                <option value="Otorga el presente" @if($doc->otorga === 'Otorga el presente') selected @endif>Otorga el presente</option>
                                <option value="Otorga la presente" @if($doc->otorga === 'Otorga la presente') selected @endif>Otorga la presente</option>
                            </select>
                        </div>
                        <div class="form-group w-75 m-auto">
                            <select class="form-control-plaintext select-center font-weight-bold"
                                    name="certificado" id="certificado">
                                <option value="CERTIFICADO" @if($doc->certificado === 'CERTIFICADO') selected @endif>CERTIFICADO</option>
                                <option value="MENCIÓN" @if($doc->certificado === 'MENCIÓN') selected @endif>MENCIÓN</option>
                                <option value="MENCIÓN DE HONOR" @if($doc->certificado === 'MENCIÓN DE HONOR') selected @endif>MENCIÓN DE HONOR</option>
                            </select>
                        </div>
                    </div>

                    <div class="my-3 text-center">
                        <p class="text-muted">
                            A: -------------------- NOMBRES DEL ESTUDIANTE --------------------------
                        </p>
                    </div>
                    {{--Descripción--}}
                    <div>
                        <div class="form-group">
                            <input type="hidden" name="description" id="description" required value="{{ $doc->description }}" />
                            <div class="text-justify inline-editor border" style="min-height: 100px">
                                {!! $doc->description !!}
                            </div>
                        </div>
                    </div>
                    {{--Fecha--}}
                    <div>
                        <b class="text-muted">Fecha</b>
                            <div class="form-group row align-items-center">
                                <div class="col p-0">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="hide_date" id="hide_date"
                                                   value="1" @if ($doc->show_date === 0 || old('hide_date') == 1) checked @endif>
                                            Ocultar Fecha
                                        </label>
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <input type="date" value="{{ $doc->date ? $doc->date : '' }}"
                                           class="form-control" name="date" id="date" placeholder="Escoja una fecha">
                                </div>

                            </div>
                    </div>
                    {{--Firmas--}}
                    <div>
                        <b class="text-muted">Firmas</b>
                        <div class="ei-multiselect">
                            <select class="selectpicker" id="signatures" required
                                    name="signatures[]" data-style="btn-normal" title="Escoja máximo 4"
                                    data-live-search="true" data-max-options="4" data-size="10" multiple>
                                @foreach ($signatures as $s)
                                    <option data-tokens="{{ $s->name }}"
                                            value="{{ $s->id }}"
                                            data-subtext="{{ $s->cargo }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <img src="{{ asset('img/doc/head-min.png') }}" alt="Footer" height="30px" />
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('design.preview', ['eventId' => $doc->event_id]) }}"
               target="_blank"
               type="button" class="btn btn-outline-secondary"><i class="fa fa-eye mr-1"></i>Previsualizar</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i> Guardar Diseño</button>
        </div>
    </form>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/select/css/bootstrap-select.css') }}"/>
@endsection
@section('js')
    <script src="{{ asset('plugins/inline-editor/ckeditor.js') }}"></script>
    <script src="{{ asset('plugins/select/js/bootstrap-select.min.js') }}"></script>
    <script>

        $(document).ready(function () {
            BalloonEditor.create( document.querySelector( '.inline-editor' ), {
                toolbar: {
                    items: ['bold', 'italic', 'fontColor', 'undo', 'redo']
                },
                language: 'es',
            })
                .then( editor => {
                    window.editor = editor;
                    editor.model.document.on( 'change:data', (e) => {
                        const data = editor.getData();
                        document.getElementById('description').value = data;
                    } );
                })
                .catch( error => {console.error( error );});
            $('#signatures').selectpicker('val', @php echo $event->signatures->pluck('id') @endphp);
        })
    </script>
@endsection
