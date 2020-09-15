<button class="ei-a-helper popover-dismiss" role="button" data-toggle="popover" data-trigger="focus"
        data-placement="bottom"
        data-content="{{ $content }}"
        title="{{ $title }}">
    <i class="fa fa-question"></i>
</button>

@push('js')
    <script>
        $('.popover-dismiss').popover({
            trigger: 'focus'
        })
    </script>
@endpush
