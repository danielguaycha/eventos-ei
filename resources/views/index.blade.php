<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Home | Escuela informática</title>
    @include('_globals._meta_icon_head')
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>

<div class="content" id="inicio">
    @include('auth.verify-notify')
    <header>
        <!-- Menu desktop--->
        <nav class="container menu-main">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/ei-logo.png') }}" alt="logo-ei">
                    <span>Escuela Informática</span>
                </a>
            </div>
            <button class="menu-toggle" id="btn-menu" title="Abrir menú">
                <i class="fi fi-menu"></i>
            </button>
            <div class="menu-list">
                <ul>
                    @auth
                        <li class="menu-profile">
                            <div class="submenu">
                                <span>
                                    {{ Auth::user()->person->name }}
                                </span>
                                <img
                                    src="{{ Avatar::create(Auth::user()->person->name . ' ' . Auth::user()->person->surname) }}"
                                    alt="Avatar">
                                <ul>
                                    @can('events.*')
                                        <li><a href="{{ route('events.index') }}">Eventos</a></li>
                                    @endcan
                                    <li>


                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Cerrar sesión
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endauth
                    <li><a class="menu-item" href="#carreras">Carreras</a></li>
                    <li><a class="menu-item" href="#areas">Áreas de estudio</a></li>
                    <li><a class="menu-item" href="#acerca-de">Acerca de</a></li>
                    <li><a class="menu-item" href="#contacto">Contacto</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Menu responsive--->
    <aside class="menu-main-movil">
        <button id="btn-close"><i class="fi fi-cancelar"></i></button>
        <ul class="menu-list-movil"></ul>
    </aside>

    <!-- Slider --->
    <section class="slider-area" id="inicio">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide" style="background-image: url({{ asset('img/home/slider-1.jpg') }});"></div>
                <div class="swiper-slide" style="background-image: url({{ asset('img/home/slider-3.jpg') }});"></div>
            </div>
            <div class="swiper-pagination"></div>

            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div>
    </section>

    <!-- Carreras -->
    <section class="carreras-area" id="carreras"
             style="background-image: url({{ asset('img/home/ei-logo-gray.png') }})">
        <div class="section-title">
            <h3>Carreras</h3>
            <span class="divider">
                    <i class="fi fi-birrete"></i>
                </span>
        </div>
        <div class="container">
            <div class="carreras-area-body row">
                <div class="col-sm-12 col-md-6 carrera-content" data-aos="zoom-out-up">
                    <div class="ei-carrera">
                        <div class="ei-carrera-header">
                            <i class="fi fi-software"></i>
                            <!-- <a href="#" class="ei-btn rounded secondary-outline sm">Ver más</a> -->
                        </div>
                        <h4>TECNOLOGÍAS DE LA INFORMACIÓN</h4>
                        <p>
                            La carrera de Tecnologías de la Información forma profesionales capacitados para la gestión
                            de la infraestructura tecnológica y sistemas de información, siendo este aspecto un eje
                            transversal en todos los sectores del país y áreas de interés público. Todas las
                            organizaciones requieren de profesionales con formación tecnológica para el tratamiento de
                            su información y gestión procesos.
                        </p>

                    </div>
                </div>
                <div class="col-sm-12 col-md-6 carrera-content" data-aos="zoom-out-up">
                    <div class="ei-carrera">
                        <div class="ei-carrera-header">
                            <i class="fi fi-programacion"></i>
                            <!-- <a href="#" class="ei-btn rounded secondary-outline sm">Ver más</a> -->
                        </div>
                        <h4>INGENIERÍA EN SISTEMAS</h4>
                        <p>
                            La carrera de Ingeniería en sistemas forma profesionales con capacidades científicas,
                            técnicas, tecnológicas y humanistas mediante la docencia e investigación formativa para que
                            sean competitivos y comprometidos con el desarrollo sostenible y sustentable del buen vivir.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--- Areas de estudio -->
    <section class="areas-estudio" id="areas">
        <div class="container">
            <div class="section-title dark">
                <h3>Áreas de estudio</h3>
                <span class="divider">
                        <i class="fi fi-birrete"></i>
                    </span>
                <p>Estas son algunas de las áreas que forman parte de nuestro curriculum académico en la Escuela de
                    informática</p>
            </div>
            <div class="topic-content">
                <div class="topics">
                    <!--Electrónica-->
                    <div data-aos="flip-up" class="topic topic-icon bg-green"><i class="fi fi-circuito"></i></div>
                    <div data-aos="flip-up" class="topic topic-text">Electrónica</div>
                    <!-- Desarrollo de sotfware -->
                    <div data-aos="flip-up" class="topic topic-icon bg-orange"><i class="fi fi-sitio-web"></i></div>
                    <div data-aos="flip-up" class="topic topic-text">Desarrollo de software</div>
                    <!-- Bases de datos -->
                    <div data-aos="flip-up" class="topic topic-icon bg-blue"><i class="fi fi-servidor"></i></div>
                    <div data-aos="flip-up" class="topic topic-text">Bases de datos</div>
                    <!-- Sistemas operativos--->
                    <div data-aos="flip-up" class="topic topic-text">Sistemas Operativos</div>
                    <div data-aos="flip-up" class="topic topic-icon bg-cyan"><i class="fi fi-servidor-1"></i></div>
                    <!-- Redes de datos -->
                    <div data-aos="flip-up" class="topic topic-text">Redes de datos</div>
                    <div data-aos="flip-up" class="topic topic-icon bg-purple"><i class="fi fi-nube-1"></i></div>
                    <!-- Ing. de software -->
                    <div data-aos="flip-up" class="topic topic-text">Ingeniería de software</div>
                    <div data-aos="flip-up" class="topic topic-icon bg-red"><i class="fi fi-redes-1"></i></div>
                </div>
            </div>
        </div>
    </section>

    <section class="acerca-de" id="acerca-de">
        <div class="container">
            <div class="section-title">
                <h3>Acerca de Nosotros</h3>
                <span class="divider">
                        <i class="fi fi-birrete"></i>
                    </span>
                <p>La Escuela de Informática forma parte de la Facultad de Ingeniería Civil en la prestigiosa
                    Universidad Técnica de Machala</p>
            </div>
            <!--Mision-->
            <div class="row ei-info-content">
                <div class="col-md-6 ei-info" data-aos="fade-right">
                    <h4 class="ei-info-title">MISIÓN</h4>
                    <div class="ei-info-cita">
                        <i class="fi fi-cita"></i>
                        <p>
                            Formar profesionales emprendedores con capacidades científico-técnicas y humanísticas, que
                            gestionen eficientemente la información, desarrollando soluciones tecnológicas con
                            principios de ingeniería y estándares de calidad, comprometidos con el desarrollo
                            socioeconómico de la población en su área de influencia.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 ei-info-img">
                    <img src="{{ asset('img/home/mision.png') }}" data-aos="fade-left" alt="Misión">
                </div>
            </div>
            <!--Vision-->
            <div class="row ei-info-content">
                <div class="col-md-6 ei-info-img">
                    <img src="{{ asset('img/home/vision.png') }}" data-aos="fade-right" alt="Visión">
                </div>
                <div class="col-md-6 ei-info" data-aos="fade-right">
                    <h4 class="ei-info-title">VISIÓN</h4>
                    <div class="ei-info-cita">
                        <i class="fi fi-cita"></i>
                        <p>
                            Ser líder regional y nacional en la formación de profesionales de Tecnologías de la
                            Información, y reconocida por su excelencia académica e investigación.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="gallery">
        <div class="grid-gallery">
            @for ($i = 1; $i <= 8; $i++)
                <div class="grid-item" data-aos="flip-up"
                     style="background-image: url({{ asset('img/home/'.$i.'.jpg') }})">
                    <a href="{{ asset('img/home/'.$i.'.jpg') }}" data-fancybox="gallery" data-caption=""></a>
                </div>
            @endfor
        </div>
    </section>

    <section class="contact" id="contacto">
        <div class="section-title">
            <h3>Contacto</h3>
            <span class="divider">
                    <i class="fi fi-birrete"></i>
                </span>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 ei-info-img" data-aos="flip-down">
                    <img src="{{ asset('img/home/contacto.png') }}" alt="Contacto">
                    <div class="ei-info-text">
                        <div class="ei-info-icon">
                            <i class="fi fi-marcador-de-posicion"></i>
                            <span>Vía Machala-Pasaje Km 5 1/2  Universidad Técnica de Machala</span>
                        </div>

                        <div class="ei-info-icon">
                            <i class="fi fi-sobre"></i>
                            <a href="mailto:info@mail.com">info@mail.com</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center" data-aos="flip-down">
                    <form action="">
                        <input type="text" placeholder="Nombre" class="ei-form"/>
                        <input type="email" placeholder="Correo" class="ei-form"/>
                        <input type="text" placeholder="Asunto" class="ei-form"/>
                        <textarea rows="3" placeholder="Mensaje" class="ei-form"></textarea>
                        <button type="submit" class="ei-btn secondary rounded">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="overlay"></section>
    <button id="btn-up" onclick="gotoTop()"><i class="fi fi-flecha-hacia-arriba"></i></button>

    <footer>
        <section class="footer-content container">
            <div class="footer-img footer-ei">
                <img src="{{ asset('img/ei-logo.png') }}" alt="Logo Escuela informática">
                <b>ESCUELA INFORMÁTICA</b>
                <p>“Si lo puedes imaginar lo puedes realizar”</p>
                <div class="social">
                    <a href="{{ route('login') }}"><i class="fi fi-configuraciones"></i></a>
                    <a href=""><i class="fi fi-sobre"></i></a>
                    <a href=""><i class="fi fi-facebook"></i></a>
                </div>
            </div>
            <div class="enlaces">
                <div class="title">Enlaces de interés</div>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#carreras">Carreras</a></li>
                    <li><a href="#areas">Áreas de estudio</a></li>
                    <li><a href="#acerca-de">Acerca de nosotros</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </div>
            <div class="enlaces">
                <div class="title">Otros enlaces</div>
                <ul>
                    <li><a href="">UTMachala</a></li>
                    <li><a href="">Facultad de Ingeniería Civil</a></li>
                    <li><a href="">Siutmach</a></li>
                    <li><a href="">Titulación UTMACH</a></li>
                    <li><a href="">Repositorio universitario</a></li>
                </ul>
            </div>
            <div class="footer-img footer-utmach">
                <img src="{{ asset('img/utm-logo.png') }}" alt="Utmach Logo">
                <p>La Escuela informática forma parte de la UTMACH, una institución de educación superior orientada a la
                    docencia, a la investigación y a la vinculación con la sociedad.</p>
            </div>
        </section>
        <section class="footer-credits">
            <div class="container">
                <div>
                    © Copyright 2020 by <a target="_blank" href="https://dguaycha.com">dguaycha.com</a>
                </div>
                <div>
                    Informáticos de corazón <b class="cora">❤</b>
                </div>
            </div>
        </section>
    </footer>
</div>

<!---scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="{{ asset('plugins/gallery.js') }}"></script>
<script src="{{ asset('js/home.js') }}"></script>
</body>

</html>
