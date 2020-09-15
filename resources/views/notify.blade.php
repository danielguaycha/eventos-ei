<div class="container-fluid p-0">
    <div class="row m-0">
    <div class="col-md-12 p-0">
    @if (session()->has('warn'))
        <div class="alert alert-warning mb-0" role="alert">
            <span>{{ session()->get('warn') }}</span>
        </div>
    @elseif(session()->has('ok'))
        <div class="alert alert-success mb-0" role="alert">
            <span>{{ session()->get('ok') }}</span>
        </div>
    @elseif(session()->has('err'))
            <div class="alert alert-danger mb-0" role="alert">
                <span>{{ session()->get('err') }}</span>
            </div>
    @elseif (session()->has('info'))
        <div class="alert alert-info mb-0" role="alert">
            <span>{{ session()->get('info') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-0">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>-&nbsp; {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    </div>
</div>
</div>
