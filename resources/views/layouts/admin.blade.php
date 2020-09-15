<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EI-Event') }}</title>

    @include('_globals._meta_icon_head')

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body>
    <div id="app" class="ei-container">

        <div class="ei-sidebar">
            <div class="ei-sidebar-header">
                <h3> {{ config('app.name', 'Escuela Informática') }}</h3>
            </div>
            <div class="ei-sidebar-body">
                {{--Usuario--}}
                <div class="ei-sidebar-user">
                    <img src="{{ asset('img/profile.png') }}" alt="user-profile" height="60" />
                    <div class="ei-sidebar-user-desc">
                        <b>{{ Auth::user()->person->name }}</b>
                        @php
                            $role = Auth::user()->roles()->first();
                        @endphp
                        @if($role)
                            {{ $role->description }}
                        @endif
                    </div>
                </div>
                {{--Menú--}}
                <div class="ei-sidebar-menu">

                    <ul>
                        @role('root')
                            <li class="ei-sidebar-divider">Usuarios</li>
                            <li><a href="{{ route('admins.index') }}" class="@if (request()->is('user/admins/*') || request()->is('user/admins')) active @endif"> <i class="fa fa-users-cog"></i>Administradores</a></li>
                        @endrole
                        @can('students.index')
                            <li><a href="{{ route('students.index') }}" class="@if (request()->is('user/students/*') || request()->is('user/students')) active @endif"> <i class="fa fa-user-alt"></i>Estudiantes</a></li>
                        @endcan
                        <li class="ei-sidebar-divider">Eventos</li>
                        @role('student')
                            <li>
                                <a href="#"><i class="fa fa-user-graduate"></i>Mis cursos</a>
                            </li>
                        @else
                            <li><a href="{{ route('events.index') }}"
                                   class="@if (request()->is('events/*') || request()->is('events')) active @endif"
                                ><i class="fa fa-graduation-cap"></i>Cursos/Eventos</a></li>

                            <li class="ei-sidebar-divider">Configuraciones</li>
                            <li>
                                <a href="{{ route('sponsor.index') }}"
                                   class="@if (request()->is('sponsor/*') || request()->is('sponsor')) active @endif">
                                    <i class="fa fa-university"></i>Organizadores</a>
                            </li>
                            <li>
                                <a href="{{ route('signatures.index') }}"
                                   class="@if (request()->is('signatures/*') || request()->is('signatures')) active @endif">
                                    <i class="fa fa-signature"></i>Firmas</a>
                            </li>
                        @endrole


                    </ul>
                </div>
            </div>
        </div>

        <div class="ei-sidebar-overlay" id="ei-overlay"></div>

        <div class="ei-parent">
            @auth
            <nav class="ei-nav">
                <div class="ei-search">
                    <button class="menu-open btn btn-link">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
                <ul class="ei-nav-items">
                    <li class="dropdown">
                        <a id="session-user" class="ei-nav-user dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img src="{{ asset('img/profile.png') }}" alt="user-profile" width="30px">
                            {{ Auth::user()->person->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="session-user">

                            <a href="#" class="dropdown-item">Perfil</a>

                            <a href="#" class="dropdown-item">Cambiar clave</a>


                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Cerrar sesión
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>


                        </div>
                    </li>
                </ul>
            </nav>
            @endauth
            {{--Parant content--}}
            <main class="ei-parent-content" class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        $(document).on('click','.delete',function(){
            let id = $(this).attr('data-id');
            let url = $(this).attr('data-url')
            $('#delete-id').val(id);
            $('#delete-form').attr("action", url);
        });
    </script>
    @yield('js')
    @stack('js')
</body>
</html>
