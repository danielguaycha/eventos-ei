@extends('layouts.admin')

@push('nav')
    @include('notify-min')
@endpush
@section('content')
    <form class="card" method="post" action="{{ route('design.update', ['id' => $doc->id ]) }}">
        @method('put')
        @csrf

        <div class="card-header">
            <b>Diseñar certificado</b>
        </div>
        <div class="card-body py-0 m-0">
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

                        </div>

                        <div class="select_logo">
                            <div class="btn-group dropleft">

                                <button type="button" class="btn dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false">
                                    @if ($doc->sponsor_logo)
                                        <img src="{{ url('/img/'.$doc->sponsor_logo) }}"
                                             height="75.38px"
                                             id="selected-img"
                                             alt="logo">
                                        <input type="hidden" name="sponsor_logo" id="sponsor_logo" value="{{ $doc->sponsor_logo  }}">
                                    @else
                                        <input type="hidden" name="sponsor_logo" id="sponsor_logo">
                                        <img id="selected-img"
                                             src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAQAAABpN6lAAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADukAAA7pAQ4zQhwAAAAHdElNRQfkCggBMTAvQ55TAAAJJUlEQVR42u2da4xcZRnHf887t+6lRtJ2d0VLS0sCAVqgsETQ6KeaSlOVXgiJtyBUErQQK6gYChY24WI12jUai4gxkEDcFosCxX7QL1xXsAtrKYG1lhLLTreA6ezOzsyZ9/HDdrd7mb3M7Jx5Znf9fdrs7pz5//9zznnfM+c9zyOEihLgRRGicf8xfx7LWcpiGljIAmpIUAOkyZDmBD2alKMc5l/ukDsWZBXBaRQJVWFoW0/jREQi2qjNXEkz57Nwyu+mHOcg7Twv7ZLUvKrXmpkTQBoRHPPlYtawmguJT2NjGTrZzz7t4CReQ4ihrAEoWcHpfFbJRj7P0jJu+jBPaxsHXIp8TMspumzbUgKnMW1iLV/lclwZNQ69hXTq49LGEclGfbmEl2U7vUQi0Vq/UjfzRT4agvXhfMhe2eVeD/qCfH01BKAEkfx8aZabdTWJkM0PkpFnfau2R1PR/HQNTOv1SuCoY5XeomundaorKQT+LDvlgKZi0zocpvHatLiELNfNep18pMLmB+nlcdkpb5KJaYUDUIJIvolNcisfNzI/yLuyg93yXjQozUpJr8oJtVyit+m6kKdpU0P5m7TIS9oXL2E/KNqAknGugU26jUXWzoeR5G72kIwXfVIs8v+VIOaX6y1yA1Frz6PI8xj3uTejueIsFfXfOaFer9AWmq3djsPLsk1e0FQxh0IRAfQ7WSDX6F1VteuP5jg/oo2ehC97AFmnTVzPHRUf74slx33skv/EpxjBFAPod7KYb7GViLW/KeClVXdyJJEvUwBKLqLLdKvcWBWD3lRQHqVF3o5NYUyYgqVshHNp0autXRWHPME2DsUn3QsmDSDjOEfu1fXWhkqI4E96G29Ndjqc5Lo96ziL22eifdB1fI8l2UkcTvjnfqdN3MTXra2UzHWyRc/MTOhxgj/mRBZwPVtnzKlvLKK3sJkF2QkcjBuAQp1cwx0zYuCbyN8P2Ch1408Nx8lGCWL6WX2UBmsHZeC4fEX+Ot41wjh7QNb5c/SeWWEfFmmLP2e8M0HBX2eFBr7LJ62Vl41m/Y5rKLwLFAhAkVrWz+BzfwHkOjZSW+hMUCCVbJQrta2qr/lKIek28EJszMxwzB6QExr9D2edfWjQ72vj2MNgVAAKCTbJ56zVhoGuZT2J0YfBqEQyjgt4mk9Yiw2JI6zljZFXByP2AMXV67dnrX1Yoje5UZOiEQEEEVbJl61Vhol8TS8ORsxthwXQS36+3kydtchQqdctQX2qcACRiDTrVdYKQ+cLcml02D4wFIASrZUtFbu/a0fCbYkOmxINBRA4v1Jn5fA3Gv28XxEM+T71g6JxvXEOfP4ACb1Bh24nnwogJyxhnbWyivElzhqcEw7uChHdEPrilurhDF2vkWEBpPH1cq21qopyra/rOx2ACKv0QmtNFeUiuUjkdABONs3grz5LQWSjuFMBpNH5rLFWVHGu0vq+gQBEuLisqzpnBsvciogMHAIic+/zB2GNCDglEmG1tRoTVktEcQE0MrdGgEFW6qIA58VfXvWrPsJhnl7mxSlcYa3EjE8pTqjaNV/h0yy4aJzzrXWYcX405vyZLLTWYUaDb3L+vDk2CR6O+HMdy6xVmLLccba1BlPOdiy21mCJLnY0WouwRBrdHB4DABY6zrDWYMoZjlprDZZovZsj9wLGQRKOmLUIU+JhPOM7o3DkrCWYknVkrDVYohlHr7UIS+Sk431rEaa87zhhrcGUE067rTVYot1OjlqLsETedfzbWoQphx1d1hpM6XLuDUquvjDj8e6Qc8c4bq3DjKTrdkGOg9Y6zDgY5JxCu7UOM14GJ/C8tQ4zngPnVNrn6AVRv7zi1EWRJJ3WWkzokJ4oTtA8+621mLBf84IDVfZZazFA2acKDrxqB4et9VScLt+ZHwigBk7yjLWeivOUpGoZXCzttW2OTYi97lbPYACq/IPXrTVVlA59TXUogBokxWPWmirKYy41cEvs1H0ByctuPrRWVTE+kD1y6vHJUwHElCPstdZVMZ7g6OAzM4N7AOTk1/RbK6sIaXmQoaeoh26NxbzrlL9Ya6sIz0hnbOj54aEAhKDPt86By6KM/iJIn14YN+zmaJDXdp6y1hc6e/XVYFgZhWEB1BNNyU5SxW9z5iD/ldZoav6w34y4PR7Ny6v6iLXIUHnQHYiOKKMxIgDB98oveddaZVjoQd3l+0YujB21QCLu3VuyY5ZeF+TlAY7ERlWXGxWAQIY2nZ3fD+xln4ypojJmiUxM6Xb3kbRWW3a6ZYf0jK03W2CNUCyQV9hOYK24rATSIh2xAvUlCwQgaB979HfWmsuJPkQb6ULPBRRcJRZXTcpPedFadtl4WX7uk4Ur0E9UTu8z+uisWEpdSjk9IZqTl9hO1lr9tMlwpzw/fsXxcRdKCtpLG/cwpfLEVYvnXnZr7/hPBU2wUjSueoLf8pMZPC1SfsZvODFRsfUJl8rO87zHr3jY2kfJ/h+iVY5NXFl4krXCCc873C+7ra2UgjwpP9Z3JiuyPuli6YSXLu6UJ6ztFG1/D7dL17xJa8z/v7j61LbY72QxN7G16hprFMLLTm0tY3n9AbJOm/gGd1T9EyYZWnhYjpW5wQIMtNhgA9urutpwN3exR3vmTXnoLuqozorUabPezaetfY7Di7JNXtTekJqsQJW32fk9O9xbobbZGYgg42QRG7irqg6FJNvZQzJedOO1kga2rEiNrtBb2VAVA2NenpIHOFChVlsDKLkIDWzQW1li614Pyv0863pK7Tw4jU8wJxpnqd6g3zRrt5fSR2iVt8klKt1ubwAl56RWL9GbWVfx+UE/T0orHb43YdVwcTCEIBLUy6Vui65hXoXMp3lGWuVVeo1bbg5yklgkWusv1M1cHXpdyg/4o+ySTtLTN1+2AGBU293mUDqTDLTd/QNHyE2vz2goAQyEkBWcr5eLZCNXsaxsW1e6eFrbeM31Vm3j5dP0ISJO690K1rCaldM6M/TTwX72+U5JqVctf7GD0CYyfUQGmq8v0su4ksu5gEVTfjfPcf5JO8/J3+W4etV8CNZDDmAAJcCLIkRjvsmfx3KW6mJpZAELqCNODZAmSy8n6NGkHOUwXe6Q6w5yiiAaC1ni/wAKHwqGgPMJqwAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAyMC0xMC0wOFQwMTo0OTo0OCswMDowMDwXA6sAAAAldEVYdGRhdGU6bW9kaWZ5ADIwMjAtMTAtMDhUMDE6NDk6NDgrMDA6MDBNSrsXAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAAABJRU5ErkJggg=="
                                             height="75.38px">
                                    @endif
                                </button>

                                <div class="dropdown-menu">
                                    <button type="button" id="logo_0" onclick="setLogo(0)" class="dropdown-item d-flex align-items-center"><i class="fa fa-ban text-danger mr-1" style="font-size: 26px" ></i>Ocultar Logo</button>
                                    <li><hr class="dropdown-divider"></li>
                                    @foreach ($sponsors as $s)
                                        @if ($s->logo)
                                            <button type="button" class="dropdown-item"
                                                    onclick="setLogo({{ $s->id }})"
                                                    id="logo_{{ $s->id }}"
                                                    data-id="{{ $s->id }}"
                                                    data-logo="{{ $s->logo }}">
                                                <img src="{{ url('/img/'.$s->logo) }}" height="27px" alt="{{ $s->name }}">
                                                {{ $s->name }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            {{---
                            <div class="select-logo-b">
                                <select name="sponsor_logo" id="sponsor_logo"
                                        class="form-control selectpicker" title="Seleccione Logo">
                                    <option value="">Ninguno....</option>
                                    @foreach ($sponsors as $e)
                                    @if ($e->logo !== null)
                                    <option @if ($e->logo === $doc->sponsor_logo) selected @endif
                                            data-content="<span><img class='logo_b' src='{{ url('/img/'.$e->logo) }}'/></span>"
                                            value="{{ $e->logo }}"></option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            ---}}
                        </div>
                    </div>
                    <div class="otorga my-2">

                        <div class="form-group w-75 m-auto">
                            <div class="sponsor">

                                <select class="form-select select-center"
                                        name="sponsor_id"
                                        id="sponsor_id">
                                    @foreach ($sponsors as $s)
                                        <option @if ($s->id === $event->sponsor_id) selected @endif
                                        value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group w-75 m-auto">
                            <select class="form-select select-center text-primary" name="otorga" id="otorga">
                                <option value="Otorga el presente" @if($doc->otorga === 'Otorga el presente') selected @endif>Otorga el presente</option>
                                <option value="Otorga la presente" @if($doc->otorga === 'Otorga la presente') selected @endif>Otorga la presente</option>
                            </select>
                        </div>
                        <div class="form-group w-75 m-auto">
                            <select class="form-select select-center font-weight-bold"
                                    name="certificado" id="certificado">
                                <option value="CERTIFICADO" @if($doc->certificado === 'CERTIFICADO') selected @endif>CERTIFICADO</option>
                                <option value="MENCIÓN" @if($doc->certificado === 'MENCIÓN') selected @endif>MENCIÓN</option>
                                <option value="MENCIÓN DE HONOR" @if($doc->certificado === 'MENCIÓN DE HONOR') selected @endif>MENCIÓN DE HONOR</option>
                            </select>
                        </div>
                    </div>

                    <div class="my-3 text-center">
                        <p class="text-muted">
                            A:  :::: NOMBRES DEL ESTUDIANTE ::::
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
                            <select class="selectpicker"
                                    id="signatures" required
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
@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select/css/bootstrap-select.css') }}"/>
@endpush
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

            // selección del logo
            document.getElementById("sponsor_id").addEventListener("change", function (e) {
                const id = e.target.value;
                setLogo(id);
            });
        });

            function setLogo(id){
                const btnLogo = $(`#logo_${id}`);
                const logo = btnLogo.data("logo");
                const img = $('#selected-img');
                const blankImg = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAQAAABpN6lAAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADukAAA7pAQ4zQhwAAAAHdElNRQfkCggBMTAvQ55TAAAJJUlEQVR42u2da4xcZRnHf887t+6lRtJ2d0VLS0sCAVqgsETQ6KeaSlOVXgiJtyBUErQQK6gYChY24WI12jUai4gxkEDcFosCxX7QL1xXsAtrKYG1lhLLTreA6ezOzsyZ9/HDdrd7mb3M7Jx5Znf9fdrs7pz5//9zznnfM+c9zyOEihLgRRGicf8xfx7LWcpiGljIAmpIUAOkyZDmBD2alKMc5l/ukDsWZBXBaRQJVWFoW0/jREQi2qjNXEkz57Nwyu+mHOcg7Twv7ZLUvKrXmpkTQBoRHPPlYtawmguJT2NjGTrZzz7t4CReQ4ihrAEoWcHpfFbJRj7P0jJu+jBPaxsHXIp8TMspumzbUgKnMW1iLV/lclwZNQ69hXTq49LGEclGfbmEl2U7vUQi0Vq/UjfzRT4agvXhfMhe2eVeD/qCfH01BKAEkfx8aZabdTWJkM0PkpFnfau2R1PR/HQNTOv1SuCoY5XeomundaorKQT+LDvlgKZi0zocpvHatLiELNfNep18pMLmB+nlcdkpb5KJaYUDUIJIvolNcisfNzI/yLuyg93yXjQozUpJr8oJtVyit+m6kKdpU0P5m7TIS9oXL2E/KNqAknGugU26jUXWzoeR5G72kIwXfVIs8v+VIOaX6y1yA1Frz6PI8xj3uTejueIsFfXfOaFer9AWmq3djsPLsk1e0FQxh0IRAfQ7WSDX6F1VteuP5jg/oo2ehC97AFmnTVzPHRUf74slx33skv/EpxjBFAPod7KYb7GViLW/KeClVXdyJJEvUwBKLqLLdKvcWBWD3lRQHqVF3o5NYUyYgqVshHNp0autXRWHPME2DsUn3QsmDSDjOEfu1fXWhkqI4E96G29Ndjqc5Lo96ziL22eifdB1fI8l2UkcTvjnfqdN3MTXra2UzHWyRc/MTOhxgj/mRBZwPVtnzKlvLKK3sJkF2QkcjBuAQp1cwx0zYuCbyN8P2Ch1408Nx8lGCWL6WX2UBmsHZeC4fEX+Ot41wjh7QNb5c/SeWWEfFmmLP2e8M0HBX2eFBr7LJ62Vl41m/Y5rKLwLFAhAkVrWz+BzfwHkOjZSW+hMUCCVbJQrta2qr/lKIek28EJszMxwzB6QExr9D2edfWjQ72vj2MNgVAAKCTbJ56zVhoGuZT2J0YfBqEQyjgt4mk9Yiw2JI6zljZFXByP2AMXV67dnrX1Yoje5UZOiEQEEEVbJl61Vhol8TS8ORsxthwXQS36+3kydtchQqdctQX2qcACRiDTrVdYKQ+cLcml02D4wFIASrZUtFbu/a0fCbYkOmxINBRA4v1Jn5fA3Gv28XxEM+T71g6JxvXEOfP4ACb1Bh24nnwogJyxhnbWyivElzhqcEw7uChHdEPrilurhDF2vkWEBpPH1cq21qopyra/rOx2ACKv0QmtNFeUiuUjkdABONs3grz5LQWSjuFMBpNH5rLFWVHGu0vq+gQBEuLisqzpnBsvciogMHAIic+/zB2GNCDglEmG1tRoTVktEcQE0MrdGgEFW6qIA58VfXvWrPsJhnl7mxSlcYa3EjE8pTqjaNV/h0yy4aJzzrXWYcX405vyZLLTWYUaDb3L+vDk2CR6O+HMdy6xVmLLccba1BlPOdiy21mCJLnY0WouwRBrdHB4DABY6zrDWYMoZjlprDZZovZsj9wLGQRKOmLUIU+JhPOM7o3DkrCWYknVkrDVYohlHr7UIS+Sk431rEaa87zhhrcGUE067rTVYot1OjlqLsETedfzbWoQphx1d1hpM6XLuDUquvjDj8e6Qc8c4bq3DjKTrdkGOg9Y6zDgY5JxCu7UOM14GJ/C8tQ4zngPnVNrn6AVRv7zi1EWRJJ3WWkzokJ4oTtA8+621mLBf84IDVfZZazFA2acKDrxqB4et9VScLt+ZHwigBk7yjLWeivOUpGoZXCzttW2OTYi97lbPYACq/IPXrTVVlA59TXUogBokxWPWmirKYy41cEvs1H0ByctuPrRWVTE+kD1y6vHJUwHElCPstdZVMZ7g6OAzM4N7AOTk1/RbK6sIaXmQoaeoh26NxbzrlL9Ya6sIz0hnbOj54aEAhKDPt86By6KM/iJIn14YN+zmaJDXdp6y1hc6e/XVYFgZhWEB1BNNyU5SxW9z5iD/ldZoav6w34y4PR7Ny6v6iLXIUHnQHYiOKKMxIgDB98oveddaZVjoQd3l+0YujB21QCLu3VuyY5ZeF+TlAY7ERlWXGxWAQIY2nZ3fD+xln4ypojJmiUxM6Xb3kbRWW3a6ZYf0jK03W2CNUCyQV9hOYK24rATSIh2xAvUlCwQgaB979HfWmsuJPkQb6ULPBRRcJRZXTcpPedFadtl4WX7uk4Ur0E9UTu8z+uisWEpdSjk9IZqTl9hO1lr9tMlwpzw/fsXxcRdKCtpLG/cwpfLEVYvnXnZr7/hPBU2wUjSueoLf8pMZPC1SfsZvODFRsfUJl8rO87zHr3jY2kfJ/h+iVY5NXFl4krXCCc873C+7ra2UgjwpP9Z3JiuyPuli6YSXLu6UJ6ztFG1/D7dL17xJa8z/v7j61LbY72QxN7G16hprFMLLTm0tY3n9AbJOm/gGd1T9EyYZWnhYjpW5wQIMtNhgA9urutpwN3exR3vmTXnoLuqozorUabPezaetfY7Di7JNXtTekJqsQJW32fk9O9xbobbZGYgg42QRG7irqg6FJNvZQzJedOO1kga2rEiNrtBb2VAVA2NenpIHOFChVlsDKLkIDWzQW1li614Pyv0863pK7Tw4jU8wJxpnqd6g3zRrt5fSR2iVt8klKt1ubwAl56RWL9GbWVfx+UE/T0orHb43YdVwcTCEIBLUy6Vui65hXoXMp3lGWuVVeo1bbg5yklgkWusv1M1cHXpdyg/4o+ySTtLTN1+2AGBU293mUDqTDLTd/QNHyE2vz2goAQyEkBWcr5eLZCNXsaxsW1e6eFrbeM31Vm3j5dP0ISJO690K1rCaldM6M/TTwX72+U5JqVctf7GD0CYyfUQGmq8v0su4ksu5gEVTfjfPcf5JO8/J3+W4etV8CNZDDmAAJcCLIkRjvsmfx3KW6mJpZAELqCNODZAmSy8n6NGkHOUwXe6Q6w5yiiAaC1ni/wAKHwqGgPMJqwAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAyMC0xMC0wOFQwMTo0OTo0OCswMDowMDwXA6sAAAAldEVYdGRhdGU6bW9kaWZ5ADIwMjAtMTAtMDhUMDE6NDk6NDgrMDA6MDBNSrsXAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAAABJRU5ErkJggg==';
                const inputLogo = $('#sponsor_logo');

                if (!logo) {
                    img.attr('src', blankImg);
                    inputLogo.val(null);
                } else {
                    if (logo === 0) {
                        img.attr('src', blankImg);
                        inputLogo.val(null);
                    } else {
                        img.attr('src', `/img/${logo}`);
                        inputLogo.val(logo);
                    }
                }
            }
    </script>
@endsection
