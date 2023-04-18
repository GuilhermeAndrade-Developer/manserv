@extends("templates.template")

@section("css")
    <link rel="stylesheet" media="screen" href="{{ asset('/assets/css/home.css') }}">
@endsection

@section("content")

<div class="container">
    <div class="row" id="cards-menu">
        <div class="col-4 float-left  height-column">
            <div class="col-12 float-left  height-big">
                <div class="flip height-big">
                    <div class="row">
                        <div class="col">
                            <div class="card" onclick="flip(event)">
                                <div class="front">
                                    <i class="material-icons menu-cards">store</i>
                                    <br>
                                    <h3 class="cardTittle">Gestão de veículos <br> &nbsp;</h3>
                                </div>
                                <div class="back mx-auto">
                                    <div class="logoo row">
                                        <div class="col">
                                            <img src="{{ asset("/assets/images/brancopng.png") }}" height="35" class="mx-auto"
                                                 style="margin-top: 5px;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <ul id="menu-gestao-veiculos">
                                            @if(!empty($menus['Gestão de Veículos']['submenu']))
                                                @foreach($menus['Gestão de Veículos']['submenu'] as $submenu)
                                                    <li><a href="{{ $submenu['url'] }}" style="color: white;"> <h6>{{ $submenu['text'] }}</h6></a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 float-left height-small">
                <div class="flip height-small">
                    <div class="row">
                        <div class="col">
                            <div class="card" onclick="flip(event)">
                                <div class="front">
                                    <i class="material-icons menu-cards">group</i>
                                    <br>
                                    <h3 class="cardTittle">Gestão de utilização de veículos <br> &nbsp;</h3>
                                </div>
                                <div class="back mx-auto">
                                    <div class="logoo row">
                                        <div class="col">
                                            <img src="{{ asset("/assets/images/brancopng.png") }}" height="35" class="mx-auto"
                                                 style="margin-top: 5px;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <ul id="menu-utilizacao-veiculos">
                                            @if(!empty($menus['Gestão de Utilização de Veículos']['submenu']))
                                                @foreach($menus['Gestão de Utilização de Veículos']['submenu'] as $submenu)
                                                    <li><a href="{{ $submenu['url'] }}" style="color: white;"> <h6>{{ $submenu['text'] }}</h6></a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4 float-left  height-column">
            <div class="col-12 float-left height-medium">
                <div class="flip height-medium">
                    <div class="row">
                        <div class="col">
                            <div class="card" onclick="flip(event)">
                                <div class="front">
                                    <img src="{{ asset("/assets/icons/pencil-alt-solid.svg") }}" height="50" alt="">
                                    <br>
                                    <h3 class="cardTittle">Cadastros <br> &nbsp;</h3>
                                </div>
                                <div class="back mx-auto">
                                    <div class="logoo row">
                                        <div class="col">
                                            <img src="{{ asset("/assets/images/brancopng.png") }}" height="35" class="mx-auto"
                                                 style="margin-top: 5px;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <ul id="menu-cadastros">
                                            @if(!empty($menus['Cadastros']['submenu']))
                                                @foreach($menus['Cadastros']['submenu'] as $submenu)
                                                    <li><a href="{{ $submenu['url'] }}" style="color: white;"> <h6>{{ $submenu['text'] }}</h6></a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 float-left height-medium">
                <div class="flip height-medium">
                    <div class="row">
                        <div class="col">
                            <div class="card" onclick="flip(event)">
                                <div class="front">
                                    <i class="material-icons menu-cards">directions_car</i>
                                    <br>
                                    <h3 class="cardTittle">Gestão de requisição de veículos<br> &nbsp;</h3>
                                </div>
                                <div class="back mx-auto">
                                    <div class="logoo row">
                                        <div class="col">
                                            <img src="{{ asset("/assets/images/brancopng.png") }}" height="35" class="mx-auto"
                                                 style="margin-top: 5px;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <ul id="menu-requisicao-veiculos">
                                            @if(!empty($menus['Gestão de Requisição de Veículos']['submenu']))
                                                @foreach($menus['Gestão de Requisição de Veículos']['submenu'] as $submenu)
                                                    <li><a href="{{ $submenu['url'] }}" style="color: white;"> <h6>{{ $submenu['text'] }}</h6></a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4 float-left  height-column">
            <div class="col-12 float-left height-big">
                <div class="flip height-big">
                    <div class="row">
                        <div class="col">
                            <div class="card" onclick="flip(event)">
                                <div class="front">
                                    <i class="material-icons menu-cards">assignment</i>
                                    <br>
                                    <h3 class="cardTittle">Relatórios <br> &nbsp;</h3>
                                </div>
                                <div class="back mx-auto">
                                    <div class="logoo row">
                                        <div class="col">
                                            <img src="{{ asset("/assets/images/brancopng.png") }}" height="35" class="mx-auto"
                                                 style="margin-top: 5px;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <ul id="menu-relatorios">
                                            @if(!empty($menus['Relatórios']['submenu']))
                                                @foreach($menus['Relatórios']['submenu'] as $submenu)
                                                    <li><a href="{{ $submenu['url'] }}" style="color: white;"> <h6>{{ $submenu['text'] }}</h6></a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12  float-left height-small">
                <div class="flip height-small">
                    <div class="row">
                        <div class="col">
                            <div class="card" onclick="flip(event)">
                                <div class="front">
                                    <i class="material-icons menu-cards">settings</i>
                                    <br>
                                    <h3 class="cardTittle">Gestão de devolução de veículos <br> &nbsp;</h3>
                                </div>
                                <div class="back mx-auto">
                                    <div class="logoo row">
                                        <div class="col">
                                            <img src="{{ asset("/assets/images/brancopng.png") }}" height="35" class="mx-auto"
                                                 style="margin-top: 5px;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <ul id="menu-devolucao-veiculos">
                                            @if(!empty($menus['Gestão de Devolução de Veículos']['submenu']))
                                                @foreach($menus['Gestão de Devolução de Veículos']['submenu'] as $submenu)
                                                    <li><a href="{{ $submenu['url'] }}" style="color: white;"> <h6>{{ $submenu['text'] }}</h6></a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section("js")

<script src="{{ asset("/assets/js/home.js") }}"></script>
@endsection
