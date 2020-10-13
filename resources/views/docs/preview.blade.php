<html>
    <head>
        <title>Documento Generado</title>
        <style>
            {!! include ('css/doc.css') !!}
        </style>
    </head>
    <body>
        @php
            $svg = QrCode::size(115)->format('svg')
                ->eyeColor(0, 26, 67, 126, 163, 32, 32)
                ->eye("square")
                ->generate("www.daniel.com");
            $html = '<img src="data:image/svg+xml;base64,'.base64_encode($svg).'" class="qr-img"  width="115" height="115" />';
        @endphp
        <header>
            <img src="{{public_path()}}/img/doc/head.png" alt="header">
            <span></span>
        </header>
        <main>
            <div class="content-cerf">
                {{--MEMBRETE---}}
                <table class="membrete">
                    <tbody>
                    <tr>
                        <td class="first-logo" >
                            <img src="{{public_path()}}/img/utm-logo.png" alt="utmach_logo">
                        </td>
                        <td class="text">
                            <h1>UNIVERSIDAD TÉCNICA DE MACHALA</h1>
                            <h3>D.I. NO. 69-04 DE 14 DE ABRIL DE 1969</h3>
                            <h2>
                                <i>&mdash;&mdash;&nbsp;</i>
                                {{ $data->sponsor }}
                                <i>&nbsp;&mdash;&mdash;</i>
                            </h2>
                        </td>
                        <td class="second-logo" >
                            @if ($data->sponsor_logo)
                                <img src="{{storage_path()}}/app/public/{{ $data->sponsor_logo }}" alt="logo_b">
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>

                {{--CUERPO---}}
                <table class="body" width="100%">
                    <tbody>
                    <tr><td  class="otorga">{{ $data->otorga }}</td></tr>
                    <tr>
                        <td  class="certificado"><h1>{{$data->certificado}}</h1></td>
                    </tr>
                    <tr>
                        <td class="person-content">
                            <div class="ab">
                                <b>A: </b>
                            </div>
                            <div class="person">
                                <h2>{Nombres completos del Estudiante}</h2>
                                <div class="line"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="description">
                            <p >
                                {!! $data->description !!}
                            </p>
                        </td>
                    </tr>
                    <tr>

                            <td class="fecha">
                                <p>
                                    Machala, {{ Carbon\Carbon::parse($data->date)->isoFormat('DD [de] MMMM [de] YYYY') }}
                                </p>
                            </td>

                    </tr>
                    </tbody>
                </table>

                {{--FIRMAS--}}
                <table class="final s-{{ count($data->signatures) }}">
                    <tbody>
                        <tr>
                            @foreach ($data->signatures as $s)
                                <td class="signatures">
                                    <img src="{{storage_path()}}/app/public/{{ $s['image'] }}" width="100px">
                                    <div>
                                        <div class="line"></div>
                                        <div>{{ $s['name'] }}</div>
                                        <b>{{ $s['cargo'] }}</b>
                                    </div>
                                </td>
                            @endforeach
                            <td class="qr">
                                {!! $html !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
        <footer>
            <span></span>
            <img src="{{public_path()}}/img/doc/head.png" alt="footer">
        </footer>
    </body>
</html>

