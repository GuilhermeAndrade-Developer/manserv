<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel='stylesheet' type='text/css' media='screen' href="{{ asset("/assets/vendor/css/bootstrap.min.css") }}">
    <link rel='stylesheet' type='text/css' media='screen' href="{{ asset("/assets/vendor/css/awesome-notification.min.css") }}">
    <link rel="stylesheet" media="screen" href="{{ asset("/assets/css/main.css") }}">

</head>
<body>
<div id="loginFrameContainer">
    <div id="loginFrame" class="row mx-auto">
        <div id="cabecalhoLogin">
            <img src="{{ asset("/assets/images/logo.png") }}" height="50" alt="">
        </div>
        <div id="loginframeContent" class="mx-auto">
            <div class="form-group">
                <i class="fas fa-sign-in-alt"></i>

                <div class="row">
                    <div class="col-1">
                        <img src="{{ asset("/assets/icons/sign-in-alt-solid.svg") }}" height="23">
                    </div>
                    <div class="col-11" style="padding-bottom: 0px">
                        <label for="inputCpf" class="h4">Login</label>
                    </div>
                </div>

                <input type="text" class="form-control myinput cpf" id="inputCpf" placeholder="Digite o CPF">
            </div>
            <div class="form-group mx-auto">

                <div class="row">
                    <div class="col-1">
                        <img src="{{ asset("/assets/icons/lock-solid.svg") }}" height="22">
                    </div>
                    <div class="col-11" style="padding-bottom: 0px">
                        <label for="inputPass" class="h4">Senha</label>
                    </div>
                </div>
                <input type="password" class="form-control myinput " id="inputPass" placeholder="Digite a senha">
            </div>
            <button id="btnLogin" class="text-white myinput">Acessar</button>
            <div id="spinner">
                <div class="loader mx-auto"></div>
            </div>
        </div>
    </div>
</div>
<!-- Início Footer -->
<script src="{{ asset("/assets/vendor/js/jquery.min.js") }}"></script>
<script src="{{ asset("/assets/vendor/js/popper.min.js") }}"></script>
<script src="{{ asset("/assets/vendor/js/jquery.mask.min.js") }}"></script>
<script src="{{ asset("/assets/vendor/js/bootstrap.min.js") }}"></script>
<script src="{{ asset("/assets/vendor/js/awesome-notification.min.js") }}"></script>
<script src="{{ asset("/assets/js/common.js") }}"></script>
<script src="{{ asset("/assets/js/actions.js") }}"></script>
<script src="{{ asset("/assets/js/login.js") }}"></script>

<!-- Final Footer -->
</body>
</html>
