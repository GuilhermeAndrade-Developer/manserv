    <div class="row mx-auto" id="cabecalho">
        <div class="col-3">
            <a href="/">
                <img src="{{ asset("/assets/images/logo_global.png") }}" height="65" alt="">
            </a>
        </div>
        <div class="col-5">

        </div>
        <div class="col-2 user">
                <i class="material-icons user" onclick="infoUser()">person</i>
            <p><b>Olá</b> <span id="nomeUsuario"></span>!</p>
            <div id="infoUser" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="col-12 text-right" style="padding-top:10px">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="info-user" id="info-user">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <button style="margin-top: 10px; width: 100px; background-color: #ff8113;" type="button"
                    class="btn text-white" id="btnSair" onclick="sair()">Sair</button>
        </div>
    </div>
    <hr>