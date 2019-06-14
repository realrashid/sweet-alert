@if(config('sweetalert.local') && !empty(config('sweetalert.cdn')))
<script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
<script>
    Swal.fire({
        "title":"Warning!",
        "html":`You can not use <b>cdn</b> and <b>local</b> together! <br />
        You can only use <b>cdn</b> or <b>local</b>! <br />
        If you want to use <b>cdn</b> goto <b>.env</b> and change <pre>SWEET_ALERT_LOCAL=false</pre>`,
        "type":"warning",
        "showConfirmButton":false,
        "showCloseButton":true,
        "allowEscapeKey":false,
        "allowOutsideClick":false
    });
</script>
@elseif(config('sweetalert.local'))
<script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    @if (Session::has('alert.config'))
<script>
    Swal.fire({!! Session::pull('alert.config') !!});
</script>
    @endif
@elseif(!is_null(config('sweetalert.cdn')))
<script src="{{ config('sweetalert.cdn') }}"></script>
    @if (Session::has('alert.config'))
<script>
    Swal.fire({!! Session::pull('alert.config') !!});
</script>
    @endif
@endif
