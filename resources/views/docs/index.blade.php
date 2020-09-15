<html>
    <head>
        <title>Documento Generado</title>
        <style>
            {!! include ('css/doc.css') !!}
        </style>
    </head>
    <body>
        @php
            $svg = QrCode::size(113)->format('svg')
                ->eyeColor(0, 26, 67, 126, 163, 32, 32)
                ->eye("square")
                ->generate("www.daniel.com");
            $html = '<img src="data:image/svg+xml;base64,'.base64_encode($svg).'"  width="113" height="113" />';
        @endphp
        <header>
            <img src="{{public_path()}}/img/doc/head.png" alt="header">
            <span></span>
        </header>
        <main>
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
                                    Centro de investigación
                                <i>&nbsp;&mdash;&mdash;</i>
                            </h2>
                        </td>
                        <td class="second-logo" >
                            <img src="{{public_path()}}/img/ei-logo.png" alt="ei_logo">
                        </td>
                    </tr>
                </tbody>
            </table>

            {{--CUERPO---}}
            <table class="body">
                <tbody>
                    <tr><td  class="otorga">Otorga el presente</td></tr>
                    <tr>
                        <td  class="certificado"><h1>CERTIFICADO</h1></td>
                    </tr>
                    <tr>
                        <td class="person-content">
                            <div class="ab">
                                <b>A: </b>
                            </div>
                            <div class="person">
                                <h2>ING. SIST. DANIEL AFRANIO GUAYCHA APOLINARIO</h2>
                                <div class="line"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="description">
                            <p >Por haber <b>ASISTIDO</b> al ciclo de Conferencias de Ingeniería de Sistemas y Tecnologias de la
                                información, realizadas del 23 al 24 de agosto de 2020.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="fecha">
                            <p>
                                Machala, 24 de agosto de 2020
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="final">
                <tbody>
                    <tr>
                        <td class="signatures">

                                <img src="{{storage_path()}}/app/public/signatures/1597769152.png" width="100px">
                                <div>
                                    <div class="line"></div>
                                    <div>Ing. Juan Carlos Berrú Ph.D</div>
                                    <b>DECANO FIC</b>
                                </div>

                        </td>
                        <td class="signatures">

                                <img src="{{storage_path()}}/app/public/signatures/1597769152.png" width="100px">
                                <div>
                                    <div class="line"></div>
                                    <div>Ing. Mariuzi Zea Ordoñez Mg.</div>
                                    <b>SUBDECANA FIC</b>
                                </div>

                        </td>
                        <td class="qr">
                            <div class="title m-b-md">
                                {!! $html !!}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            {{---<h1>Daniel, {{ $titulo }}</h1>---}}
        </main>
        <footer>
            <span></span>
            <img src="{{public_path()}}/img/doc/head.png" alt="footer">
        </footer>
    </body>
</html>

