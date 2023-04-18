<div id="assocUsuarioUtModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h4 class="modal-title">Associar Usuario a UT</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <form autocomplete="off">
                                    <div class="autocomplete" style="width:100%;">
                                        <input id="us_input" list="usuario" name="usuario" placeholder="Procure um Usuário por CPF" class='usuario'>
                                        <datalist id="usuario">                                           
                                        </datalist>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <form autocomplete="off">
                                    <div class="autocomplete" style="width:100%;">
                                        <input id="ut_input" list="ut" name="ut" placeholder="Procure uma UT" class='ut'>
                                        <datalist id="ut">                                            
                                        </datalist>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <input id="criarCondutor" onclick="associarUsuarioUt()" type="button"
                            class="btn btn-success" value="Cadastrar">
                    </div>
            </form>
        </div>
    </div>
</div>