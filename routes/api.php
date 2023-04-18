<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

Route::group(['namespace' => 'Classis'], function () {

    Route::group(['namespace' => 'Loads'], function () {
        Route::get('loads/uts', 'LoadsController@ut_cc')->name('load.uts');
        Route::get('loads/usuarios', 'LoadsController@usuarios')->name('load.usuarios');
        Route::get('loads/gestores', 'LoadsController@gestores_ut')->name('load.gestores');
    });

    /** Rota Login Portal */
    Route::post('login', 'API\AuthController@login')->name('login');
    /** Redefinição de Senha */
    Route::post('/redefinir-senha', 'API\AuthController@redefinir_senha')->name('redefinir-senha');

    Route::group(['middleware' => 'auth.jwt'], function () {

        Route::group(['namespace' => 'Admin\ACL'], function () {
            /** Rota de Perfil */
            Route::get('/perfis', 'PerfilController@index')->name('perfis');
            Route::get('/perfil/{id}', 'PerfilController@show')->name('perfil.show');
            Route::post('/perfil', 'PerfilController@store')->name('perfil.store');
            Route::put('/perfil/{id}', 'PerfilController@update')->name('perfil.update');
            Route::delete('/perfil/{id}', 'PerfilController@destroy')->name('perfil.delete');

            /** Rota de Permissoes */
            Route::get('/permissoes', 'PermissaoController@index')->name('permissoes');
            Route::get('/permissoes/perfil/{id}', 'PermissaoController@show')->name('permissoes.show');
            Route::post('/permissoes', 'PermissaoController@store')->name('permissoes.store');
            Route::put('/permissoes/perfil/{id}', 'PermissaoController@update')->name('permissoes.update');
            Route::delete('/permissoes/perfil/{id}', 'PermissaoController@destroy')->name('permissoes.delete');

            Route::get('perfil/{id}/regra/disponivel', 'PermissaoController@rulesAvailable')->name('regra.disponivel');
            Route::get('regra/{id}/perfil/disponivel', 'PermissaoController@profilesAvailable')->name('perfil.disponivel');

            /** Rota de Permissoes Customizadas */
            Route::get('/permissoes/custom', 'PermissaoCustomController@index')->name('permissoes.custom');
            Route::get('/permissoes/custom/usuario/{id}', 'PermissaoCustomController@show')->name('permissoes.custom.show');
            Route::get('/permissoes/custom/usuario/lista', 'PermissaoCustomController@list')->name('permissoes.custom.list');
            Route::post('/permissoes/custom', 'PermissaoCustomController@store')->name('permissoes.custom.store');
            Route::put('/permissoes/custom/usuario/{id}', 'PermissaoCustomController@update')->name('permissoes.custom.update');
            Route::delete('/permissoes/custom/usuario/{id}', 'PermissaoCustomController@destroy')->name('permissoes.custom.delete');

            /** Rota de Regras */
            Route::get('/regras', 'RegraController@index')->name('regras');
            Route::get('/regra/{id}', 'RegraController@show')->name('regra.show');
            Route::post('/regra', 'RegraController@store')->name('regra.store');
            Route::put('/regra/{id}', 'RegraController@update')->name('regra.update');
            Route::delete('/regra/{id}', 'RegraController@destroy')->name('regra.delete');
        });

        Route::group(['namespace' => 'API'], function () {

            /** Unidades Federativas */
            Route::get('lista-estados', 'UnidadeFederativaController@listaEstados')->name('lista-estados');
            Route::get('cidades/{id}/estado', 'UnidadeFederativaController@cidades')->name('cidades');
            Route::get('cidade/{id}', 'UnidadeFederativaController@cidade')->name('cidade');

            /** Rota para Redefinição de Senha */
            Route::post('resetar-senha/{id}', 'AuthController@resetar_senha')->name('resetar-senha');

            /** Rota para a página principal do Portal ou tela principal do APP */
//        Route::get('/', 'HomeController@index')->name('home');

            /** Rota para as Pendências no Portal ou APP. */
            Route::get('/pendencias/{id}', 'PendenciaController@index')->name('pendencias');

            /** Rota Gerenciamento de Condutor */
            Route::get('condutor/listacondutor', 'CondutorController@lista_condutor')->name('condutor.listacondutor');
            Route::post('condutor/registrarcondutor', 'CondutorController@registrar_condutor')->name('condutor.registrarcondutor');
            Route::get('condutor/mostrarcondutor/{id}', 'CondutorController@mostrar_condutor')->name('condutor.mostrarcondutor');
            Route::put('condutor/atualizarcondutor', 'CondutorController@atualizar_condutor')->name('condutor.atualizarcondutor');
            Route::delete('condutor/removercondutor/{id}', 'CondutorController@remover_condutor')->name('condutor.removercondutor');
            Route::get('condutor/lista_condutor_ut/{id}', 'CondutorController@condutoresPorUT')->name('condutor.lista.condutor.ut');
            Route::get('condutor/verificar-veiculo/{id}', 'CondutorController@verificar_veiculos')->name('condutor.verificarcarro');
            Route::get('condutor/listacondutorusuario/{usuario}/{ut?}', 'CondutorController@retornarUsuariosCondutor')->name('condutor.usuario');
            Route::get('condutor/usuario/{id}', 'CondutorController@usuario_condutor')->name('condutor.usuario_condutor');

            /** Rotas dos usuários representante */
            Route::get('usuarios-representantes/', 'UsuarioRepresentanteController@lista')->name('usuariosrepresentantes.lista');
            Route::post('usuarios-representantes/associar', 'UsuarioRepresentanteController@gravar')->name('usuariosrepresentantes.gravar');
            Route::get('usuarios-representantes/{id}', 'UsuarioRepresentanteController@exibir')->name('usuariosrepresentantes.exibir');
            Route::delete('usuarios-representantes/{id}', 'UsuarioRepresentanteController@apagar')->name('usuariosrepresentantes.apagar');

            /** Rotas de Associação de Usuário a UT */
            Route::get('consulta-usuario-ut/{id}', 'AssociarUsuarioUtController@show')->name('associarusuariout.show');
            Route::post('associar-usuario/','AssociarUsuarioUtController@store')->name('associarusuariout.store');

            /** Rotas de Requisição de Veículos */
            Route::get('requisicao-veiculos/prazo-req', 'RequisicaoVeiculoController@calculaPrazoReq')->name('requisicaoveiculos.prazo.requisicao');
            Route::get('requisicao-veiculos', 'RequisicaoVeiculoController@index')->name('requisicaoveiculos.list');
            Route::get('requisicao-veiculos/{status}/status', 'RequisicaoVeiculoController@index')->name('requisicaoveiculos.list.status');
            Route::post('requisicao-veiculos', 'RequisicaoVeiculoController@store')->name('requisicaoveiculos.store');
            Route::get('requisicao-condutor/', 'RequisicaoVeiculoController@condutor')->name('requisicaoveiculos.condutor');
            Route::get('uts_associadas/', 'RequisicaoVeiculoController@associadas')->name('requisicaoveiculos.associadas');
            Route::get('requisicao-veiculos-uts-usuario/{id}/{status?}', 'RequisicaoVeiculoController@requisicao_uts_usuario')->name('requisicaoveiculos.ut.usuario');
            Route::get('requisicao-veiculos/{id}', 'RequisicaoVeiculoController@show')->name('requisicaoveiculos.show');
            Route::put('requisicao-veiculos/{id}', 'RequisicaoVeiculoController@update')->name('requisicaoveiculos.update');
            Route::delete('requisicao-veiculos/{id}', 'RequisicaoVeiculoController@destroy')->name('requisicaoveiculos.destroy');


            /** Rotas de Detalhes de Requisição de Veiculos  */
            Route::get('/detalhe_requisicao_veiculo/{id}', 'DetalhesRequisicaoVeiculosController@show')->name('detalherequisicaoveiculo.show');
            Route::put('/atualizar-status-detalhes/{id}/{status}', 'DetalhesRequisicaoVeiculosController@atualizarStatus')->name('atualizar.status');
            
            
            /** Rotas de Usuários */
            Route::group(['prefix' => 'usuarios'], function () {
                Route::get('/', 'UsuarioController@lista')->name('usuarios.lista');
                Route::get('me/{id}', 'UsuarioController@perfil')->name('usuario.me');
                Route::post('consulta-cpf/', 'UsuarioController@exibir')->name('usuarios.exibir');
                Route::get('filtrar-uts-usuarios/{id}', 'UsuarioController@filtrar')->name('usuarios.filtrar');
                Route::get('representa-ut/{id}', 'UsuarioController@representa')->name('usuarios.uts.representada');
                Route::get('gerencia-ut/{id}', 'UsuarioController@gerencia')->name('usuarios.uts.gerencia');
                Route::get('representante-gerencia-ut/{id}', 'UsuarioController@gerenciaAll')->name('usuarios.uts.representante.gerencia');
            });

            /** Rotas de UT */
            Route::get('lista-ut/', 'UtController@lista')->name('ut.lista');
            Route::get('filtrar-ut/{id}', 'UtController@filtrarUt')->name('ut.filtrar');

            /** Rotas de Aprovação de Requisição de Veículos  */
            Route::get('aprovacao-requisicao/', 'AprovacaoRequisicaoVeiculoUtController@lista')->name('aprovacaorequisicao.lista');
            Route::get('aprovacao-requisicao-filtro/{id?}', 'AprovacaoRequisicaoVeiculoUtController@show')->name('aprovacaorequisicao.show');
            Route::get('aprovacao-requisicao/usuario/{id}/{requisitante?}', 'AprovacaoRequisicaoVeiculoUtController@lista_usuario')->name('aprovacaorequisicao.lista_usuario');
            Route::post('aprovacao-requisicao-aprovar', 'AprovacaoRequisicaoVeiculoUtController@aprovar')->name('aprovacaorequisicao.aprovar');
            Route::post('aprovacao-requisicao-reprovar', 'AprovacaoRequisicaoVeiculoUtController@reprovar')->name('aprovacaorequisicao.reprovar');

            /** Rotas Voucher */
            Route::post('registrar-voucher/', 'VoucherController@store')->name('voucher.registar');
            
            Route::get('show-voucher-lista/{id}/requisicao_veiculo', 'VoucherController@showVoucherPorRequisicao')->name('voucherrequisicao.lista');

            /** Rota para extração de arquivo de voucher */
            Route::post('converter-pdf', 'ConverterPDFController@converterPDF')->name('pdf.converter');
            
            
            Route::get('lista-voucher/', 'VoucherController@lista')->name('voucher.lista');
            Route::get('voucher-condutor/', 'VoucherController@condutor')->name('voucher.condutor');
            Route::get('voucher/{id}', 'VoucherController@show')->name('voucher.show');
            Route::put('voucher/{id}', 'VoucherController@update')->name('voucher.update');

            /** Rotas Veiculo Condutor */

            /**
             * verificar se todas as consultas devem serem feitas obrigatóriamente por uts    
             */
            Route::get('lista-veiculos/{usuario?}/{ut?}/{status?}', 'VeiculosController@index')->name('veiculo.index');
            Route::get('veiculo-apresentar/{id}', 'VeiculosController@apresentarVeiculo')->name('veiculo.apresentarVeiculo');
            Route::get('lista-veiculo-condutor/{idCondutor}', 'VeiculosController@show')->name('veiculo.show');
            Route::get('lista-veiculo-devolucao', 'VeiculosController@listarVeiculosDevolucao')->name('veiculo.listar-devolucao');
            Route::post('adicionar-veiculo/', 'VeiculosController@store')->name('veiculo.store');
            Route::put('liberar-veiculo/{id}', 'VeiculosController@liberarVeiculo')->name('veiculo.liberarVeiculo');
            Route::put('atualizar-veiculo/{id}', 'VeiculosController@atualizarVeiculo')->name('veiculo.atualizar');
            Route::put('transferir-ut/{id}', 'VeiculosController@tranferenciaUt')->name('veiculo.tranferencia-ut');
            Route::delete('inativar-veiculo/{idVeiculo}', 'VeiculosController@destroy')->name('veiculo.destroy');

            /** Rotas Detalhes Veiculos */
            Route::get('lista-detalhes-veiculo/', 'DetalheVeiculoController@index')->name('detalhesveiculo.index');
            Route::post('gravar-detalhes-veiculo', 'DetalheVeiculoController@store')->name('detalhesveiculo.store');
            Route::get('detalhes-veiculo/{id}', 'DetalheVeiculoController@show')->name('detalhesveiculo.show');
            Route::put('atualizar-detalhes-veiculo/{id}', 'DetalheVeiculoController@update')->name('detalhesveiculo.update');
            Route::delete('remover-detalhes-veiculo/', 'DetalheVeiculoController@destroy')->name('detalhesveiculo.destroy');

            /** Rotas Pendencias */
            Route::get('lista-pendencias-veiculos/', 'PendenciasVeiculosController@index')->name('pendenciasveiculos.index');
            Route::post('gravar-pendencias-veiculos/', 'PendenciasVeiculosController@store')->name('pendenciasveiculos.store');
            Route::get('pendencias-veiculos/{id}', 'PendenciasVeiculosController@show')->name('pendenciasveiculos.show');
            Route::put('atualizar-pendencias-veiculos/', 'PendenciasVeiculosController@update')->name('pendenciasveiculos.update');
            Route::delete('remover-pendencias-veiculos/', 'PendenciasVeiculosController@destroy')->name('pendenciasveiculos.destroy');

            /** Rotas Termo Condutor */
            Route::get('termos-usuario/', 'TermosUsuariosController@index')->name('termousuario.index');
            Route::get('ler-termo/{idTermo}', 'TermosUsuariosController@show')->name('termousuario.show');
            Route::get('verificar-aceite/{idUsurio}', 'TermosUsuariosController@verificar')->name('termousuario.verificar');
            Route::post('aceite-termo/', 'TermosUsuariosController@store')->name('termousuario.store');

            /** Rotas Termo Criador */
            Route::get('listar-termos/', 'TermosController@index')->name('pendenciasveiculos.index');
            Route::post('gravar-termos/', 'TermosController@store')->name('pendenciasveiculos.store');
            Route::delete('inativar-termos/', 'TermosController@destroy')->name('pendenciasveiculos.destroy');

            /** Rotas Ocorrencias */
            Route::get('lista-ocorrencias','OcorrenciasVeiculosController@index')->name('ocorrencias.index');
            Route::get('lista-ocorrencia/veiculo/{idVeiculo}/condutor/{idCondutor}','OcorrenciasVeiculosController@exibir')->name('ocorrencias.exibir');
            Route::get('detalhar-ocorrencia/{idOcorrencia}','OcorrenciasVeiculosController@show')->name('ocorrencias.show');
            Route::post('gravar-ocorrencias','OcorrenciasVeiculosController@store')->name('ocorrencias.store');
            Route::delete('remover-ocorrencias/','OcorrenciasVeiculosController@destroy')->name('ocorrencias.destroy');

            /** Rotas Preposto */
            Route::get('preposto/', 'PrepostoController@index')->name('preposto.lista');
            Route::post('preposto/cadastrar', 'PrepostoController@store')->name('preposto.store');
            Route::get('preposto/{id}', 'PrepostoController@show')->name('preposto.mostra');
            Route::put('preposto/atualizarpreposto/{id}', 'PrepostoController@update')->name('preposto.update');

            /** Rotas Locadora */
            Route::get('locadoras/', 'LocadorasController@index')->name('locadoras.index');

            /** Rotas Checklist Veiculos*/
            Route::get('lista-checklists','ChecklistController@index')->name('checklist.index');
            Route::get('lista-checklist/{placa}/{id_condutor}','ChecklistController@exibir')->name('checklist.exibir');
            Route::post('gravar-checklist','ChecklistController@store')->name('checklist.store');
            Route::post('gravar-checklist-images','ChecklistController@files')->name('checklist.files');
            Route::delete('remover-checklist/{id_checklist}','ChecklistController@destroy')->name('checklist.destroy');

             /** Rotas Checklist Itens*/
             Route::get('lista-checklists-itens','ChecklistItensController@index')->name('checklistitens.index');
             Route::get('detalhar-checklists-itens/{id}','ChecklistItensController@show')->name('checklistitens.show');
             Route::post('gravar-checklists-itens','ChecklistItensController@store')->name('checklistitens.store');
             Route::delete('remover-checklists-itens/','ChecklistItensController@destroy')->name('checklist.destroy');

             /** Rota de Cálculo de Combustível */
            Route::get('calcula-limite-cartao', 'CartaoCombustivelController@calculaLimiteCartao')->name('limite-cartao');
            
            /** Rota de Marca de Veiculo*/
            Route::get('listar-marca', 'MarcaController@listar')->name('marca.listar');

            /** Rota de Modelo de Veiculo*/
            Route::get('listar-modelo/{marca}/marca', 'ModeloController@listarModeloPorMarca')->name('modelo.listar-marca');
            
        });
        
    });
    
    Route::get('teste', function () {
        $d = '2020-10-10';
        // $data = Carbon::parse($d)->add('1025 days')->format('d/m/Y');
        // $data = Carbon::now()->subDays(5)->format('Y-m-d');
        $data = new Carbon();
        dd($data->format('Y-m-d'));
    });

    
//    Route::get('valor-cartao', function (\Illuminate\Http\Request $request) {
//        // FIXME-Avelino: Parâmetros para cálculo do combustível: cat=1&capac=1&km=5000&local=2&uf=26
//        $query = $request->query();
//
//        if (empty($query)) {
//            return 'Não foi possível realizar o cálculo!';
//        }
//
//        $valor = \App\Models\ValorCombustivel::
//            where('categoria', $query['cat'])
//            ->where('capacidade', $query['capac'])
//            ->where('km', $query['km'])
//            ->where('local', $query['local'])
////            ->where('estado', $query['uf'])
//            ->first();
//
//        $limiteCartao = ($valor->km / $valor->autonomia) * $valor->valor;
//
//        return number_format($limiteCartao, '2', ',', '.');
////        return $query;
//    });
});

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::group(['namespace' => 'Testes'], function() {

        Route::get('listar-pendencias-testes', 'Teste@listarPendencias');
        Route::post('incluir-checklist', 'Teste@criarChecklist');

    });
});