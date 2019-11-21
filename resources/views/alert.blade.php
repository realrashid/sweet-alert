@if(!empty(config('sweetalert.animatecss')) && config('sweetalert.animation.enable'))
    <link rel="stylesheet" href="{{ config('sweetalert.animatecss') }}">
@endif
@if (!empty($cdn))
    @if (Session::has('alert.config'))
        <script src="{{ $cdn }}"></script>
        <script>
            Swal.fire({!! Session::pull('alert.config') !!});
        </script>
    @endif
@else
    @if (Session::has('alert.config'))
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script>
        Swal.fire({!! Session::pull('alert.config') !!});
    </script>
    @endif
@endif
