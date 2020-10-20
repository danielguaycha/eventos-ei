@extends('layouts.app')

@section('content')
    <div class="container d-flex">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="ei-verify">
                    <div class="head">
                        <a href="/">
                            <img src="{{ asset('img/ico/favicon-32x32.png') }}" alt="ei-logo">
                        </a>
                        <div>
                            <a href="{{ url('/') }}" class="mr-1">Home</a> |&nbsp;
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Salir
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        </div>
                    </div>
                    <div class="title">
                        <h3>Verifique su email para finalizar</h3>
                    </div>
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <p>Tu proceso de registro esta en curso</p>
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    Si no has recibido el email, usa el botón reenviar.
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit"
                                class="btn btn-primary btn-block mt-4">Reenviar Email
                        </button>
                        .
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
