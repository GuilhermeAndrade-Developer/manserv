@extends("templates.template")

@section("css")
    <link rel="stylesheet" media="screen" href="{{ asset('/assets/css/condutor.css') }}">
    
@endsection


@section("content")
<div class="container geral">
        <div class="row">
            <div class="col-md-12">
                <div class="container">
                    <div class="container">
                        <br>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#cadastro">Cadastrar Condutor</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#multas">Multas</a>
                            </li>
                            <li class="nav-item">
                                <a  class="nav-link disabled" data-toggle="tab" href="#ocorrencias">Ocorrências</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">                          
                        <div id="cadastro" class="container tab-pane active"><br>
                            <div class="table-wrapper">
                                <div class="table-title">
                                    <div class="row">
                                        <div class="col-sm-6">
                                        </div>

                                        <div class="col-sm-6"
                                            style="text-align: right; align-items: flex-end; margin-bottom: 10px;">
                                            <button type="button" class="btn btn-success add-condutor" onclick="adicionarCondutor()">
                                                <i class="material-icons">&#xE147;</i> <span>Adicionar Condutor</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <!--th>CPF</th-->
                                            <th>CNH</th>
                                            <th>Categoria da CNH</th>
                                            <th>Vencimento da CNH</th>
                                            <th>Visualizar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaCondutores">
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            <div id="multas" class="container tab-pane fade"><br>

                                <!-- linha 1 -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Placa</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Data</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Hora</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- linha 2 -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>N.Auto de infração</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Tipo Infração</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Pontuação</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- linha 3 -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Descrição multa</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Valor da multa</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Valor com desconto</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- linha 4 -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Valor da taxa</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Fator da multa</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Valor pelo fator multa</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- linha 5 -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Endereço</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Cidade</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Estado</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- linha 6 -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Proprietário do veículo</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Status motorista</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <h6>Reincidentes</h6>
                                        </div>
                                        <div class="row">
                                            <input readonly type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div id="ocorrencias" class="container tab-pane fade"><br>

                            </div>
                        </div>
                    </div>
@include('front.includes.modal.modal_condutor')
@endsection

@section("js")
<script src="{{ asset('/assets/js/condutor.js') }}"></script>
<script src="{{ asset('/assets/js/actions.js') }}"></script>
@endsection
