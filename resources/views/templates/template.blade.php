<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto|Varela+Round'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/icon?family=Material+Icons'>
    <link rel='stylesheet' type='text/css' media='screen' href="{{ asset("/assets/vendor/css/bootstrap.min.css") }}">
    <link rel='stylesheet' type='text/css' media='screen' href="{{ asset("/assets/vendor/css/awesome-notification.min.css") }}">
    <link rel="stylesheet" media="screen" href="{{ asset("/assets/css/style.css") }}">
    @hasSection("css")
        @yield("css")
    @endif

</head>

<body>

<!-- Início Nav -->
@include("front.includes.nav")
<!-- Final Nav -->

<!-- Início Content -->
@yield("content")
<!-- Final Content -->

<!-- Início Footer -->
<script src="{{ asset("/assets/vendor/js/jquery.min.js") }}"></script>
<script src="{{ asset("/assets/vendor/js/popper.min.js") }}"></script>
<script src="{{ asset("/assets/vendor/js/bootstrap.min.js") }}"></script>
<script src="{{ asset("/assets/vendor/js/jquery.mask.min.js") }}"></script>
<script src="{{ asset("/assets/vendor/js/awesome-notification.min.js") }}"></script>
<script src="{{ asset("/assets/js/api.js") }}"></script>
<script src="{{ asset("/assets/js/common.js") }}"></script>
<script src="{{ asset("/assets/js/verificaPermissao.js") }}"></script>
<script src="{{ asset("/assets/js/actions.js") }}"></script>
@hasSection("js")
    @yield("js")
@endif

<!-- Final Footer -->
</body>
</html>
