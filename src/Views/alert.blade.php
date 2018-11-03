<script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@if (Session::has('alert.config'))
    <script>
        swal({!! Session::pull('alert.config') !!});
    </script>
@endif
