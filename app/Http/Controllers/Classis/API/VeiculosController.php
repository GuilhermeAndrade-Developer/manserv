<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Condutores;
use App\Models\Veiculos;
use App\Models\Pendencias;
use App\Models\DocumentacaoVeiculos;
use App\Models\Checklists;
use App\Models\ChecklistItems;
use App\Models\ChecklistVeiculos;
use App\Models\DetalhesRequisicaoVeiculos;
use App\Models\VouchersLocadoras;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class VeiculosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @ param int $id
     */
    public function index($usuario = null, $ut = null, $status = null)
    {
        
        try {
            
            $prepareVeiculos = Veiculos::with('modelo.marca')
            ->with('voucher')
            ->with('locadora')
            ->with('ut');
            
            if($status) {
                $prepareVeiculos->where('status', $status);
            }
            
            if($ut) {
                $prepareVeiculos->where('id_ut_cc', $ut);
            }

            $tmpVeiculos = $prepareVeiculos->get();
            
            if(!$tmpVeiculos->count()) {
                return response()->json([
                    'message' => 'Não Existem Veiculos para listar',
                    'status' => 'OK',
                    'data' => []                   
                ], 200);
            }
            
            foreach ($tmpVeiculos as $veiculo) {
                $checklist = Checklists::where('placa', $veiculo->placa)
                ->orderBy('id', 'desc')
                ->limit(1)
                ->first();

                $veiculos[] = [
                    "id" => $veiculo->id,
                    "placa" => $veiculo->placa,
                    "categoria" => $veiculo->categoria,
                    "combustivel" => $veiculo->combustivel,
                    "ano_modelo" => $veiculo->ano_modelo,
                    "id_modelo" => $veiculo->id_modelo,
                    "proprio_alugado" => $veiculo->proprio_alugado,
                    "renavam" => $veiculo->renavam,
                    "meio_pagamento" => $veiculo->meio_pagamento,
                    "nome_meio_pagamento" => $veiculo->nome_meio_pagamento,
                    "numero_meio_pagamento" => $veiculo->numero_meio_pagamento,
                    "limite_meio_pagamento" => $veiculo->limite_meio_pagamento,
                    "fornecedor_meio_pagamento" => $veiculo->fornecedor_meio_pagamento,
                    "vencimento_meio_pagamento" => $veiculo->vencimento_meio_pagamento,
                    "cartao_combustivel" => $veiculo->cartao_combustivel,
                    "nome_cartao_combustivel" => $veiculo->nome_cartao_combustivel,
                    "numero_cartao_combustivel" => $veiculo->numero_cartao_combustivel,
                    "limite_cartao_combustivel" => $veiculo->limite_cartao_combustivel,
                    "fornecedor_cartao_combustivel" => $veiculo->fornecedor_cartao_combustivel,
                    "vencimento_cartao_combustivel" => $veiculo->vencimento_cartao_combustivel,
                    "rastreador" => $veiculo->rastreador,
                    "nome_rastreador" => $veiculo->nome_rastreador,
                    "numero_rastreador" => $veiculo->numero_rastreador,
                    "fornecedor_rastreador" => $veiculo->fornecedor_rastreador,
                    "vencimento_rastreador" => $veiculo->vencimento_rastreador,
                    "seguro" => $veiculo->seguro,
                    "cobertura_seguro" => $veiculo->cobertura_seguro,
                    "numero_apolice" => $veiculo->numero_apolice,
                    "seguradora" => $veiculo->seguradora,
                    "vencimento_seguro" => $veiculo->vencimento_seguro,
                    "mes_licenciamento" => $veiculo->mes_licenciamento,
                    "id_contrato_locadora" => $veiculo->id_contrato_locadora,
                    "id_locadora" => $veiculo->id_locadora,
                    "id_grupo" => $veiculo->id_grupo,
                    "status" => $veiculo->status,
                    "modelo" => $veiculo->modelo,
                    "checklist" => $checklist,
                    "ut" => $veiculo->ut,
                    'locadora' => $veiculo->locadora,
                    'voucher' => $veiculo->voucher
                ];
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel executar a consulta de Veiculos!',
                'mensagem' => $e->getMessage(),
                'code' => $e->getCode(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de Veiculos!',
            'status' => 'OK',
            'data' => $veiculos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $dados = $request->all();
            $dados['status'] = 1;
            
            $veiculo = Veiculos::create($dados);
            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Erro ao gravar Veiculo!',
                'code' => $e->getCode()." ".$e->getMessage(),
                'status' => 'ERROR'
            ], 500);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Erro ao gravar Veiculo!',
                'code' => $e->getCode()." ".$e->getMessage(),
                'status' => 'ERROR'
            ], 500);
        } 

        return response()->json([
            'message' => 'Veiculo cadastrado com sucesso!',
            'status' => 'OK',
            'data' => $veiculo
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $checklist = Checklists::where('id_condutor',$id)->first();
            
            if($checklist==null){
                return response()->json([
                    'message' => 'Condutor não possui um carro Designado',
                    'status' => 'OK'
                ], 200);
            }  
        $voucher = "";

            if($checklist->id_veiculo==null){
                return response()->json([
                    'message' => 'Condutor possui um carro para Retirar',
                    'status' => 'OK'
                ], 200);
            }
            
            $veiculo = Veiculos::with('detalhVeiculo')->where('id',$checklist->id_veiculo)->first();     
           
            $veiculos = null;                
                $veiculos[] = [
                    "id" => $veiculo->id,
                    "placa" => $veiculo->placa,
                    "categoria" => $veiculo->categoria,
                    "combustivel" => $veiculo->combustivel,
                    "ano_fabricacao" => $veiculo->ano_fabricacao,
                    "ano_modelo" => $veiculo->ano_modelo,
                    "id_modelo" => $veiculo->id_modelo,
                    "proprio_alugado" => $veiculo->proprio_alugado,
                    "id_contrato_locadora" => $veiculo->id_contrato_locadora,
                    "data_liberacao_retirada" => $veiculo->data_liberacao_retirada,
                    "id_locadora" => $veiculo->id_locadora,
                    "id_ut_cc" => $veiculo->id_ut_cc,
                    "id_documentacao" => $veiculo->id_documentacao,
                    "detalhes"=>$veiculo->detalheVeiculo,
                ];                       
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel executar a consulta de Veiculos!',
                'code' => $e->getCode(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de Veiculos!',
            'status' => 'OK',
            'data' => $veiculos
        ], 200);
    }

    /**
     * ------------------------------------------------------------------------
     */
    public function ApresentarVeiculo($id) 
    {

        try{
            $veiculoPrepare = Veiculos::with('modelo.marca')
            ->with('voucher')
            ->with('locadora')
            ->with('ut')
            ->where('id', $id)->first();

            
                 
            if(!$veiculoPrepare){
                return response()->json([
                    'message' => 'Veículo não Encontrado',
                    'status' => 'OK'
                ], 412);
            }
            
            $checklist = Checklists::where("id_veiculo", $veiculoPrepare->id)
            ->with('condutor.usuario')
            ->orderBy('id', 'desc')
            ->get();

            $veiculo = [
                "id" => $veiculoPrepare->id,
                "placa" => $veiculoPrepare->placa,
                "categoria" => $veiculoPrepare->categoria,
                "combustivel" => $veiculoPrepare->combustivel,
                "ano_modelo" => $veiculoPrepare->ano_modelo,
                "id_modelo" => $veiculoPrepare->id_modelo,
                "id_grupo" => $veiculoPrepare->id_grupo,
                "proprio_alugado" => $veiculoPrepare->proprio_alugado,
                "renavam" => $veiculoPrepare->renavam,
                "meio_pagamento" => $veiculoPrepare->meio_pagamento,
                "nome_meio_pagamento" => $veiculoPrepare->nome_meio_pagamento,
                "numero_meio_pagamento" => $veiculoPrepare->numero_meio_pagamento,
                "limite_meio_pagamento" => $veiculoPrepare->limite_meio_pagamento,
                "fornecedor_meio_pagamento" => $veiculoPrepare->fornecedor_meio_pagamento,
                "vencimento_meio_pagamento" => $veiculoPrepare->vencimento_meio_pagamento,
                "cartao_combustivel" => $veiculoPrepare->cartao_combustivel,
                "nome_cartao_combustivel" => $veiculoPrepare->nome_cartao_combustivel,
                "numero_cartao_combustivel" => $veiculoPrepare->numero_cartao_combustivel,
                "limite_cartao_combustivel" => $veiculoPrepare->limite_cartao_combustivel,
                "fornecedor_cartao_combustivel" => $veiculoPrepare->fornecedor_cartao_combustivel,
                "vencimento_cartao_combustivel" => $veiculoPrepare->vencimento_cartao_combustivel,
                "rastreador" => $veiculoPrepare->rastreador,
                "nome_rastreador" => $veiculoPrepare->nome_rastreador,
                "numero_rastreador" => $veiculoPrepare->numero_rastreador,
                "fornecedor_rastreador" => $veiculoPrepare->fornecedor_rastreador,
                "vencimento_rastreador" => $veiculoPrepare->vencimento_rastreador,
                "seguro" => $veiculoPrepare->seguro,
                "cobertura_seguro" => $veiculoPrepare->cobertura_seguro,
                "numero_apolice" => $veiculoPrepare->numero_apolice,
                "seguradora" => $veiculoPrepare->seguradora,
                "vencimento_seguro" => $veiculoPrepare->vencimento_seguro,
                "mes_licenciamento" => $veiculoPrepare->mes_licenciamento,
                "id_contrato_locadora" => $veiculoPrepare->id_contrato_locadora,
                "id_locadora" => $veiculoPrepare->id_locadora,
                "status" => $veiculoPrepare->status,
                "modelo" => $veiculoPrepare->modelo,
                "checklist" => $checklist,
                'ut' => $veiculoPrepare->ut,
                'locadora' => $veiculoPrepare->locadora,
                'voucher' => $veiculoPrepare->voucher
            ];

            return response()->json([
                'message' => 'Veiculo encontrado com sucesso',
                'status' => 'OK',
                'data' => $veiculo,
            ], 200);


        } catch (\Execption $e){
            return response()->json([
                'message' => 'Veiculo encontrado com sucesso',
                'code' => $e->getCode(),
                'status' => 'ERRO',
                'data' => null,
            ], 404);

        }

    }

    public function liberarVeiculo(Request $request, $id)
    {
        // dd($request);
        try{
            DB::beginTransaction();
            $veiculo = Veiculos::find($id);
            $veiculo->status = 2;
            $veiculo->save();
            // dd($veiculo);
             $pendencia = [
                'condutor_id' => $request->condutor,
                'tipo' => 'A',
                'abertura' => $request->data_liberacao,
                'status' => '2'
            ];
            // return response()->json($pendencia);
    
            Pendencias::create($pendencia);
            DB::commit();

        } catch (QueryException $e) {

            DB::rollback();
            return response()->json([
                'message' => 'Erro ao Atualizar Pendencias de Condutor',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 500);
        } catch (\Exception $e){
            
            DB::rollback();
            return response()->json([
                'message' => 'Erro ao Atualizar Pendencias de Condutor',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'data' => $request->all(),
                'status' => 'ERROR'
            ], 500);
        }

        return response()->json([
            'message' => 'Veiculo Resrvado com Sucesso',
            'status' => 'OK'
        ], 201);
    }

    public function atualizarVeiculo(Request $request, $id)
    {
        try{

            $veiculo = Veiculos::find($id);
            $veiculo->fill($request->all());
            $veiculo->save();

        }catch(QueryException $qe ) {
            return response()->json([
                'message' => 'Erro ao Atualizar Veiculo',
                'code' => $qe->getCode() . ' ' . $qe->getMessage(),
                'status' => 'ERROR'
            ], 500);

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Erro ao Atualizar Veiculo',
                'code' => $e->getCode() . ' ' . $e->getMessage(),
                'status' => 'ERROR'
            ], 500);
        }

        return response()->json([
            'message' => 'Veiculo Atualizado com Sucesso',
            'status' => 'OK', 
            'data' => $veiculo
        ], 201);
    }
    /**
     * Undocumented function
     *-------------------------------------------------
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function tranferenciaUt(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $checklist = Checklists::where('id_veiculo', $id)
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
            $checklist->data_chklist = date('Y-m-d H:i:s', strtotime("now"));
            $checklist->tipo_chklist = 9;
            $checklist->id_ut_cc = $request->id_ut_cc;
            $clone = $checklist->toArray();
            Checklists::create($clone);
            
            $veiculo = Veiculos::find($id);
            $veiculo->id_ut_cc = $request->id_ut_cc;
            $veiculo->save();
            DB::commit();

        } catch (QueryExeption $qe){
            DB::rollback();
            return response()->json([
                'message' => 'Erro ao Transferir Veiculo de UT',
                'code' => $qe->getCode() . ' ' . $qe->getMessage(),
                'status' => 'ERROR',
                'data' => [],
            ], 500);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'message' => 'Erro ao Transferir Veiculo de UT',
                'code' => $e->getCode() . ' ' . $e->getMessage(),
                'status' => 'ERROR',
                'data' => [],
            ], 500);
        }
        return response()->json([
            'message' => 'Veicullo Transferido com Sucesso',
            'status' => 'OK',
            'data' => ['id' => $veiculo->id]
        ], 201);
    }

    /*
     * ----------------------------------------------------------
     *
     * @param [type] $id
     * @return void
     */
    public function listarVeiculosDevolucao() 
    {
        // $data = new Carbon();
        // $data->format('Y-m-d');
        // $veiculo = VouchersLocadoras::where("data_devolucao", ">=", "adddate(CURDATE(), '15 DAYS')")->get();
        // dd($veiculo);

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }   
}