<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequisicaoVeiculoRequest;
use App\Models\AprovacaoRequisicaoVeiculoUt;
use App\Models\Condutores;
use App\Models\GestoresUt;
use App\Models\RequisicaoVeiculos;
use App\Models\DetalhesRequisicaoVeiculos;
use App\Models\Usuarios;
use App\Models\UsuariosRepresentantes;
use App\Models\UT;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RequisicaoVeiculoController extends Controller
{

    private $id_usuario;
    /**
     * 0	Todos
     * 1	Pendente Coordenador
     * 2	Pendente Gerente
     * 3	Diretor
     * 4	Pendente Vice-Presidente
     * 5	Pendente Presidente
     * 6	Pendente Suprimento
     * 7	Pendente Frota
     * 8	Pendente Preposto
     * 9	Atendimento Preposto
     * 10	Finalizada
     * 11	Reprovada
     * 12	Cancelada
     */


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request, int $status = null)
    {
        /**
         * NÃO MEXER NESTE END-POINT - AINDA ESTOU MONTANDO A LÓGICA DELE PARA CONCLUIR.
         * ESTE É APENAS UM PALIATIVO PARA O POVO TRABALHAR.
         * GRATO: AVELINO
         */

        if (! is_null($status)) {
            $requisicoes = RequisicaoVeiculos::with('detalhesVeiculos')
                ->with('ut')
                ->with('gestores')
                ->with('aprovacaoRequisicao')
                ->where('status', $status)
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $requisicoes = RequisicaoVeiculos::with('detalhesVeiculos')
                ->with('ut')
                ->with('gestores')
                ->with('aprovacaoRequisicao')
                ->orderBy('id', 'DESC')
                ->get();
        }

        return response()->json([
            'message' => 'Listagem processada com sucesso!',
            'status' => 'OK',
            'data' => $requisicoes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $req = $request->all();

        try{
            DB::beginTransaction();
            $ultima = RequisicaoVeiculos::all()->last();            
            $ultima = ($ultima==null)? null: $ultima->numero;
            $requisicaoRequest = [];
            $veiculos = [];
            $requisicao = new RequisicaoVeiculos();

            $requisicaoRequest['id_requisitante'] = $req['id_requisitante'];
            $requisicaoRequest['id_ut_cc'] = $req['centroDeCusto'];
            $requisicaoRequest['previsto_fpv'] = $req['fpv'] === true ? 'S': "N";            
            $requisicaoRequest['numero'] = numeroRequisicao($ultima);
            $requisicaoRequest['data_requisicao'] = $req['dataRequisicao'];            
            $requisicaoRequest['tipo'] = $req['tipo'];
            $requisicaoRequest['status'] = 0;
            $requisicaoRequest['observacao'] = $req['justificativa'];

            if (!isset($req['veiculos'])) {
                return response()->json([
                    'message' => 'Ooops! Não é possível inserir uma solicitação sem veículos!',
                    'status' => 'ERROR'
                ], 406);
            }
            if (!isset($req['justificativa'])) {
                return response()->json([
                    'message' => 'Ooops! Não é possível inserir uma solicitação sem justificativa!',
                    'status' => 'ERROR'
                ], 406);
            }
            $novaRequisicao = $requisicao->create($requisicaoRequest);
            $items = 1;
            if (is_array($req['veiculos'])) {
                foreach ($req['veiculos'] as $veiculo) {
                    
                    $veiculoTemp['id_requisicao'] = $novaRequisicao->id;
                    $veiculoTemp['categoria_veiculo'] = $veiculo['categoria_veiculo'];
                    $veiculoTemp['item'] = $items;
                    $veiculoTemp['id_condutor'] = $veiculo['id_condutor'];
                    $veiculoTemp['cidade_retirada'] = $veiculo['altCidadeRetirada'];
                    $veiculoTemp['estado_retirada'] = $veiculo['altUfRetirada'];
                    $veiculoTemp['data_retirada'] = $veiculo['data_retirada'];
                    $veiculoTemp['data_devolucao'] = $veiculo['data_devolucao'];
                    $veiculoTemp['prazo_locacao'] = $veiculo['prazo_locacao'];
                    $veiculoTemp['local_rodagem'] = $veiculo['local_rodagem'];
                    $veiculoTemp['km_mensal'] = $veiculo['km_mensal'];
                    $veiculoTemp['limite_cartao_combustivel'] = $veiculo['limite_cartao_combustivel'];
                    $veiculoTemp['status_veiculo'] = 0;
                    
                    $veiculos[] = DetalhesRequisicaoVeiculos::create($veiculoTemp);
                    
                      $items++;
                      
                      if (!isset($veiculos)) {
                          DB::rollBack();
                          return response()->json([
                              'message' => 'Ooops! Ocorreu um erro ao inserir um veículo na requisição!',
                              'status' => 'ERROR'
                          ], 406);
                      }
                }
            }

            $data['requisicao'] = $novaRequisicao;
            $data['veiculos'] = $veiculos;

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ooops! Não foi possível efetivar sua solicitação!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
                ], 406);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ooops! Erro ao processar requisição',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
            ], 406);
        }

        return response()->json([
            'message' => 'Requisição de Veículo cadastrada com sucesso!',
            'status' => 'OK',
            'data' => $data,
            'req' => $req
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $data = [];
        $detalhesVeiculos = [];

        try {
            
            $requisicao = RequisicaoVeiculos::find($id);
            $detalhes = DetalhesRequisicaoVeiculos::with('cidade_ret')->where('id_requisicao', $requisicao->id)->get();
            $ut = UT::find($requisicao->id_ut_cc);
            $usuario = Usuarios::find($requisicao->id_requisitante);
            $hitoricoAprovacao = AprovacaoRequisicaoVeiculoUt::with('requisicao')->where('id_num_requisicao', $requisicao->id)->get();
            
            $data['id'] = $requisicao->id;
            $data['numero'] = $requisicao->numero;
            $data['id_ut_cc'] = $requisicao->id_ut_cc;
            $data['previsto_fpv'] = $requisicao->previsto_fpv;
            $data['id_requisitante'] = $requisicao->id_requisitante;
            $data['data_requisicao'] = $requisicao->data_requisicao;
            $data['status'] = $requisicao->status;
            $data['justificativa'] = $requisicao->observacao;
            $data['tipo'] = $requisicao->tipo;
            
            foreach ($detalhes as $detalhe) {
                $temp = [];
                $condutorTemp = [];
                $temp['id'] = $detalhe->id;
                $temp['id_requisicao'] = $detalhe->id_requisicao;
                $temp['categoria_veiculo'] = $detalhe->categoria_veiculo;
                $temp['item'] = $detalhe->item;
                $temp['id_condutor'] = $detalhe->id_condutor;
                $temp['data_retirada'] = $detalhe->data_retirada;
                $temp['prazo_locacao'] = $detalhe->prazo_locacao;
                $temp['data_devolucao'] = $detalhe->data_devolucao;
                $temp['cidade_retirada'] = $detalhe->cidade_ret;
                $temp['estado_retirada'] = $detalhe->estado_retirada;
                $temp['local_rodagem'] = $detalhe->local_rodagem;
                $temp['km_mensal'] = $detalhe->km_mensal;
                $temp['limite_cartao_combustivel'] = $detalhe->limite_cartao_combustivel;
                $temp['id_voucher'] = $detalhe->id_voucher;
                $temp['status_veiculo'] = $detalhe->status_veiculo;
                
                
                // $condutor = Condutores::with('usuario')->where('id', $detalhe->id_condutor)->toSql();
                $condutor = Condutores::with('usuario')->where('id', $detalhe->id_condutor)->first();
                $condutorTemp['id_condutor'] = $detalhe->id_condutor;
                $condutorTemp['nome'] = $condutor->usuario->nome;
                $condutorTemp['cpf'] = $condutor->usuario->cpf;
                $condutorTemp['cnh'] = $condutor->cnh;
                $condutorTemp['categoria_cnh'] = $condutor->categoria_cnh;
                $condutorTemp['vencimento_cnh'] = $condutor->data_vencimento_cnh;
                
                $temp['condutor'] = $condutorTemp;
                
                array_push($detalhesVeiculos, $temp);
            }
            
            $data['detalhes_veiculos'] = $detalhesVeiculos;
            $data['ut'] = (object) $ut;
            $data['usuario'] = (object) $usuario;
            $tempHist = null;
            
            if($hitoricoAprovacao!=null){
                foreach($hitoricoAprovacao as $historico){
                    $usuario = Usuarios::where('id', $historico->id_gestor)->first();
                    $tempHist[]=[
                        'numero_requisicao'=>$historico->requisicao->numero,
                        'aprovador' => $usuario->nome,
                        'cargo' => tipoGestor($historico->tipo_gestor),
                        'status'=>($historico->status==1) ? 'Aprovado':'Reprovado',
                        'data'=>Carbon::parse($historico->data_aprovacao)->format('d/m/Y')
                    ];
                }
            }
            $data['aprovacao_requisicao'] = $tempHist;
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao recuperar a requisição de veículo!',
                'code' => $e->getcode(),
                'line' => $e->getLine(),
                'status' => 'ERROR'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao processar a requisição de veículo!',
                'code' => $e->getCode().$e->getMessage(),
                'line' => $e->getLine(),
                'status' => 'ERROR'
            ], 500);
        }

        return response()->json([
            'message' => 'Requisição de veículos recuperada com sucesso!',
            'status' => 'OK',
            'data' => (object) $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RequisicaoVeiculoRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        
        try{
            DB::beginTransaction();
            $requisicaoRequest = [];
            $veiculos = [];
            $req = $request->all();
            $req['previsto_fpv'] = $req['previsto_fpv'] == true ? 'S': "N";

            if (!isset($req['observacao'])) {
                return response()->json([
                    'message' => 'Ooops! Não é possível atualizar uma solicitação sem justificativa!',
                    'status' => 'ERROR'
                ], 406);
            }
            if (!isset($req['veiculos'])) {
                return response()->json([
                    'message' => 'Ooops! Não é possível atualizar uma solicitação sem veículos!',
                    'status' => 'ERROR'
                ], 406);
            }           
            $veiculosNovos = $req['veiculos'];           
            unset($req['veiculos']);
            
            $requisicaoUpdate = RequisicaoVeiculos::where('id',$id)->update($req);
            $remover = DetalhesRequisicaoVeiculos::where('id_requisicao',$id)->delete();          
            $items = 1;
            if (is_array($veiculosNovos)) {
                foreach ($veiculosNovos as $veiculo) {

                    $veiculoTemp['id_requisicao'] =  $id;
                    $veiculoTemp['categoria_veiculo'] = $veiculo['categoria_veiculo'];
                    $veiculoTemp['item'] = $items;
                    $veiculoTemp['id_condutor'] = $veiculo['id_condutor'];
                    $veiculoTemp['cidade_retirada'] = $veiculo['altCidadeRetirada'];
                    $veiculoTemp['estado_retirada'] = $veiculo['altUfRetirada'];
                    $veiculoTemp['data_retirada'] = $veiculo['data_retirada'];
                    $veiculoTemp['data_devolucao'] = $veiculo['data_devolucao'];
                    $veiculoTemp['prazo_locacao'] = $veiculo['prazo_locacao'];
                    $veiculoTemp['local_rodagem'] = $veiculo['local_rodagem'];
                    $veiculoTemp['km_mensal'] = $veiculo['km_mensal'];
                    $veiculoTemp['limite_cartao_combustivel'] = $veiculo['limite_cartao_combustivel'];
                    $veiculoTemp['status_veiculo'] = 0;

                    $veiculos[] = (new DetalhesRequisicaoVeiculos())->create($veiculoTemp);

                    if (!isset($veiculos)) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Ooops! Ocorreu um erro ao atualizar um veículo na requisição!',
                            'status' => 'ERROR'
                        ], 406);
                    }
                    $items++;
                }
            }

            $data['requisicao'] = $requisicaoUpdate;
            $data['veiculos'] = $veiculos;

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ooops! Não foi possível efetivar sua solicitação!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
            ], 406);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ooops! Erro ao processar requisição',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
            ], 406);
        }

        return response()->json([
            'message' => 'Requisição de Veículo cadastrada com sucesso!',
            'status' => 'OK',
            'data' => $data
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();
            $requisicao = RequisicaoVeiculos::find($id);
            $requisicao->delete();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocorreu um erro ao excluir a requisição de veículo!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocorreu um erro ao processar a exclusão da requisição de veículo!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }
        return response()->json([
            'message' => 'Requisição de veículo excluída com sucesso!',
            'status' => 'OK'
        ], 200);
    }

    public function condutor (Request $request) {
        
        try {
            $where = $request->query();
            $key = key($where);
            $value = $where[$key];
            $condutor_requisicao = Usuarios::with("condutor")->where($key, 'like', "{$value}%")->get();
            $condutores = [];
            foreach ($condutor_requisicao as $condutor) {
                $condutores[] = [
                    "id" => $condutor->condutor->id,
                    "nome" => $condutor->nome,
                    "cpf" => $condutor->cpf,
                    "cnh" => $condutor->condutor->cnh,
                    "categoria" => $condutor->condutor->categoria_cnh,
                    "validade" => $condutor->condutor->data_vencimento_cnh,
                    "rg" => $condutor->condutor->rg
                ];
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possivel encontrar o Condutor',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 400);
        }
        return response()->json([
            'message' => "Condutor encontrado",
            "status" => "OK",
            "total" => count($condutores),
            "condutores" => $condutores
        ], 200);
    }

    public function associadas(Request $request)
    {
        try {
            $representante = UsuariosRepresentantes::with('usuario')->with('ut')->where('id_usuario', $request->usuario)->get();

            $ut_principal = [];
            $associados = [];

            $usuario = Usuarios::with('ut')->where('id', $request->usuario)->first();
            $ut_principal["id"] = $usuario->id_ut_cc;
            $ut_principal["numero"] = $usuario->ut->numero_ut;
            $ut_principal["nome"] = $usuario->ut->descricao;

            $gestor = GestoresUt::with(['gestorUt', 'uts'])->where('id_gestor', $request->usuario)->first();

            if (!empty($representante)) {
                foreach($representante as $ut) {
                    $temp["id"] = $ut->id_ut_permitida;
                    $temp["numero"] = $ut->ut->numero_ut;
                    $temp["nome"] = $ut->ut->descricao;
                    array_push($associados, $temp);
                }
            }

            $retorno = [
                "usuario_id" => $request->usuario,
                "ut_principal" => $ut_principal,
                "uts_associadas" => $associados
            ];

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar Uts',
                'code' => $e->getCode(),
                'status' => 'ERROR',
            ], 404);
        }

        return response()->json([
            'message' => 'Ut listadas!',
            'status' => 'OK',
            'data' => $retorno
        ], 200);

    }

    public function requisicao_uts_usuario(int $id, int $status = null)
    {
        $data = [];
        $detalhesVeiculos = [];

        try {
            $usuario = Usuarios::find($id);
            $uts[] = $usuario->id_ut_cc;
            if($usuario->isRepresentative($id)){
                $representante = $usuario->hasRepresentative($id);
                foreach($representante as $ut)
                {                   
                  if(!in_array($ut->id,$uts))
                    {
                        $uts[] = $ut['id'];                        
                    }
                }
            } 
                      
            if($usuario->isManager($id)){                
                $gestor = $usuario->hasManager($id);                        
                foreach($gestor as $ut)
                {
                  if(!in_array($ut['id'],$uts))
                    {
                        $uts[] = $ut['id'];                       
                    }
                }               
            }
           if($usuario->perfil==4){
                $prepareRequisicoes = RequisicaoVeiculos::with('detalhesVeiculos')
                ->with('ut')
                ->with('gestores')
                ->with('aprovacaoRequisicao')
                ->where('status',7);
           }
           elseif($usuario->isManager($id))
           {
                $prepareRequisicoes = RequisicaoVeiculos::with('detalhesVeiculos')
                ->with('ut')
                ->with('gestores')
                ->with('aprovacaoRequisicao')
                ->whereIn('id_ut_cc',$uts);

           }else{
                $prepareRequisicoes = RequisicaoVeiculos::with('detalhesVeiculos')
                ->with('ut')
                ->with('gestores')
                ->with('aprovacaoRequisicao')
                ->where('id_requisitante',$id)
                ->whereIn('id_ut_cc',$uts);
           }

           $status ? $prepareRequisicoes->where('status', $status) : null;
           $requisicoes = $prepareRequisicoes->get();  
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao recuperar a requisição de veículo!',
                'code' => $e->getMessage(),
                'status' => 'ERROR'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao processar a requisição de veículo!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
            ], 500);
        }

        return response()->json([
            'message' => 'Requisição de veículos recuperada com sucesso!',
            'status' => 'OK',
            'data' => $requisicoes
        ], 200);
    }

    /**
     * @param Request
     * @return JsonResponse
     */
    public function calculaPrazoReq(Request $request)
    {
        $dados = $request->only(["dataRetirada", "prazoLocacao"]);

        $dataDevolucao = (new Carbon($request->dataRetirada))->addMonth($request->prazoLocacao);

        return response()->json([
            'status' => 'OK',
            'dia' => $dataDevolucao->day,
            'mes' => $dataDevolucao->month,
            'ano' => $dataDevolucao->year,
            'data' => $dataDevolucao
        ], 200);
    }
}