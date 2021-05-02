@push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            @this.on('editing', function () {
                @this.__instance.el.querySelector('.is-editing').querySelector('select, input').focus();
            });
        });
    </script>
@endpush
