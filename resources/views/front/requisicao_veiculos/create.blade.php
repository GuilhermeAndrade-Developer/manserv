@extends("templates.template")

@section("css")

    <link rel="stylesheet" media="screen" href="{{ asset('/assets/vendor/css/jquery-ui.css') }}">
    <link rel="stylesheet" media="screen" href="{{ asset('/assets/css/requisicaoveiculos.css') }}">
@endsection

@section('content')

<div class="container">
    <form class="requisicaoveiculos">
        <div class="row">
            <h4 class="title-manserv">Requisicação para Locação de Veículo</h4>
            <div class="col-2"></div>
            <div class="col-2">
                <div class="form-group">
                    <label>Data da Requisição</label>
                    <input readonly id="dataRequisicao" type="text" class="form-control" value="{{ \Carbon\Carbon::parse(now())->format('d/m/Y') }}">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label>Número da Requisição</label>
                    <input readonly id="numeroRequisicao" type="text" class="form-control" value="0000-000.000">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label>Coligada</label>
                    <input readonly id="coligada" type="text" class="form-control"
                           value="{{ !empty($ut) ? $ut['numero_coligada'] : "" }}">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label>Centro de Custo</label>
                    <input readonly id="centroDeCusto" type="text" class="form-control"
                           value="{{ !empty($ut) ? $ut["numero_ut"] : "" }}">
                </div>
            </div>
            <div class="col-3"></div>
            <div class="col-2">
                <div class="form-group">
                    <label>Está previsto na FPV?</label>
                    <div class="row text-center mx-auto">
                        <input type="button" value="Sim">
                        &nbsp;
                        <input type="button" value="Não">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label>Nome UT</label>
                    <input readonly id="nomeUt" type="text" class="form-control"
                           value="{{ !empty($ut) ? $ut["descricao"] : "" }}">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label>Endereço da UT</label>
                    <input readonly id="enderecoUt" type="text" class="form-control"
                           value="{{ !empty($ut) ? $ut["cidade"] : "" }}">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label>E-mail Requisitante</label>
                    <input readonly id="emailRequisitante" type="mail" class="form-control"
                           value="{{ !empty($usuario) ? $usuario['email'] : '' }}">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label>Telefone Requisitante</label>
                    <input readonly id="telefoneRequisitante" type="phone" class="form-control"
                           placeholder="(xx) 0000-0000">
                </div>
            </div>
        </div>

        <div class="bd-example">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label>Categoria Veículo *</label>
                        <select id="categoriaVeiculo" name="categoriaVeiculo" class="form-control add-condutor">
                            <option value="">Selecione uma Opção</option>
                            <option value="1">Designado - Diretor Geral</option>
                            <option value="2">Designado - Demais Diretores</option>
                            <option value="3">Designado - Gerente</option>
                            <option value="4">Transporte de Pessoas - (Ex: Uno)</option>
                            <option value="5">Transporte de Carga sem Baú - (Ex: Strada)</option>
                            <option value="6">Transporte de Carga com Baú - (Ex: Fiorino)</option>
                            <option value="7">Operacional - Especificar nas Observações</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label>RE Condutor *</label>
                        <input id="reCondutor" type="text" class="form-control add-condutor cpf">
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label>Nome Condutor *</label>
                        <input id="nomeCondutor" name="nomeCondutor" type="text" class="form-control add-condutor">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label>Nr. CNH Condutor *</label>
                        <input id="cnhCondutor" type="text" class="form-control cnh add-condutor">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label>Data Retirada *</label>
                        <input id="dataRetirada" type="date" class="form-control data add-condutor">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Prazo de Locação (meses) *</label>
                        <select name="prazoLocacao" id="prazoLocacao" class="form-control">
                            <option value="">Selecione uma Opção</option>
                            <option value="1">1 Mês</option>
                            <option value="2">2 Meses</option>
                            <option value="3">3 Meses</option>
                            <option value="4">4 Meses</option>
                            <option value="5">5 Meses</option>
                            <option value="6">6 Meses</option>
                            <option value="7">7 Meses</option>
                            <option value="8">8 Meses</option>
                            <option value="9">9 Meses</option>
                            <option value="10">10 Meses</option>
                            <option value="11">11 Meses</option>
                            <option value="12">12 Meses</option>
                            <option value="24">24 Meses</option>
                            <option value="36">36 Meses</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Data Devolução</label>
                        <input id="dataDevolucao" type="date" class="form-control data add-condutor">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Cidade de Retirada UF *</label>
                        <input id="cidadeRetirada" type="text" class="form-control add-condutor">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label>Local de Rodagem *</label>
                        <select id="localRodagem" name="statusCondutor" class="form-control add-condutor">
                            <option value="">Selecione uma Opção</option>
                            <option value="Estrada">Estrada</option>
                            <option value="Urbano">Urbano</option>
                            <option value="Urbano + estrada">Urbano + estrada</option>
                            <option value="Confinado">Confinado</option>
                            <option value="Confinado Acidentado">Confinado Acidentado</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>KM Mensal *</label>
                        <input id="kmMensal" type="text" class="form-control add-condutor">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Limite Cartão Combustível *</label>
                        <input id="limiteCartaoCombustivel" type="text" class="form-control add-condutor">
                    </div>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-12">
                    <input id="addCondutor" onclick="" type="button" class="btn btn-manserv" value="Adicionar">
                </div>
            </div>
        </div>

        <div class="highlight">
            <Table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Condutor</th>
                    <th scope="col">Cidade de Retirada</th>
                    <th scope="col">Categoria Veículo</th>
                    <th scope="col">Data Retirada</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="listaCondutores">

                </tbody>
            </Table>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group" id="cpfCondutorfull">
                    <label>Justificativa/Observação</label>
                    <input id="cpfCondutor" type="text" class="form-control cpf">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group" id="cpfCondutorfull">
                    <label>Nome do Requisitante</label>
                    <input id="nomeRequisitante" type="text" class="form-control"
                        value="{{ !empty($usuario) ? $usuario["nome"] : "" }}">
                </div>
            </div>
        </div>
        <div class="cadastrar">
            <input id="requisitarVeiculos" onclick="" type="button" class="btn btn-manserv"
                   value="Cadastrar">
        </div>
    </form>
</div>
@endsection

@section('js')


<script src=" https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('assets/js/requisicaoDeVeiculos.js') }}"></script>
@endsection