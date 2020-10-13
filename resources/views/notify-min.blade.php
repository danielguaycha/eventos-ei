@if (session()->has('warn'))
    <div class="alert alert-warning mb-0" role="alert">
        <i class="fa fa-exclamation"></i>
        <span>{{ session()->get('warn') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif(session()->has('ok'))
    <div class="alert alert-success mb-0" role="alert">
        <i class="fa fa-check"></i>
        <span>{{ session()->get('ok') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif(session()->has('err'))
    <div class="alert alert-danger mb-0" role="alert">
        <i class="fa fa-times"></i>
        <span>{{ session()->get('err') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif (session()->has('info'))
    <div class="alert alert-info mb-0" role="alert">
        <i class="fa fa-exclamation"></i>
        <span>{{ session()->get('info') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mb-0 fade show">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li><span class="fa fa-exclamation-triangle"></span> {{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@push('js')
    <script>
        setTimeout(() => {
            $('.alert').alert('close')
        }, 7000)
    </script>
@endpush
