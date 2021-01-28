<div id="modalCondutor" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h4 class="modal-title">Condutor</h4>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="cpfCondutorfull">
                        <label>CPF</label>
                        <input id="cpfCondutor" type="text" class="form-control cpf" required>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label>Nome</label>
                                <input readonly id="nomeCondutor" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-4" hidden>
                            <div class="form-group">
                                <label>Id</label>
                                <input readonly id="idCondutor" type="text" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>CNH</label>
                        <input id="cnhCondutor" type="text" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Categoria da CNH</label>
                                <input id="categoriaCnhCondutor" type="text" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Rg</label>
                                <input id="rgCondutor" type="text" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-7">
                            <div class="form-group">
                                <label>Vencimento da CNH</label>
                                <input id="vencimentoCnhCondutor" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Status</label>
                                <input id="statusCondutor" type="text" class="form-control" value="A" readonly >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="editarCondutor" type="button" class="btn btn-secondary"
                        value="Editar">
                </div>
                <div class="modal-footer">
                    <input id="criarCondutor" onclick="cadastrarCondutor()" type="button" class="btn btn-success"
                        value="Cadastrar">
                </div>
            </form>
        </div>
    </div>
</div>