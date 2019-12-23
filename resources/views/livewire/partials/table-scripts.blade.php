@push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            @this.on('editing', function () {
                @this.root.el.querySelector('td:nth-of-type(2)').querySelector('select, input').focus();
            });
        });
    </script>
@endpush
