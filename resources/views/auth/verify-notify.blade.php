@auth
    @if (!Auth::user()->email_verified_at && !session('resent'))
        <nav class="nav danger center">
            <div>
                Verifica tu correo para completar el proceso de registro.
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit"
                            class="ei-btn link white">Reenviar Email
                    </button>
                </form>
            </div>
            <button class="close-nav" onclick="closeNav()"><i class="fi fi-cancelar-1"></i></button>
        </nav>
    @endif

    @if (session('resent'))
        <nav class="nav success center">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </nav>
    @endif
@endauth
