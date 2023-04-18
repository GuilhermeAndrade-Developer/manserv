<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Condutores;
use App\Models\PendenciasVeiculos;
use App\Models\Checklists;
use App\Models\DetalheVeiculo;
use App\Models\ChecklistItems;
use App\Models\DetalhesRequisicaoVeiculos;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class PendenciasVeiculosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @ param int $id
     */
    public function index()
    {
        
        try {
            $tmpPendencias = PendenciasVeiculos::all();
            $checklist = null;
            $detalhe = null;
            $voucher = null;
            $pendencias = null;
            foreach ($tmpPendencias as $pendencia) {
              
                if($pendencia->tipo == 'C'){
                    
                    $checklists = Checklists::with('checklistVeiculo')->where('id',$pendencia->id_origem)->first();
                    $itemsChk = null;
                    if($checklists!=null){                   
                    foreach($checklists->checklistVeiculo as $items)
                    {               
                        $itemsChk[] = [
                                        "id" => $items->id,
                                        "id_condutor" => $items->id_condutor,
                                        "id_veiculo" => $items->id_veiculo,
                                        "id_checklist" => $items->id_checklist,
                                        "id_checklistitem" => $items->id_checklistitem,
                                        "item_nome"=> ChecklistItems::where('id',$items->id_checklistitem)->first(['item'])->item,
                                        "status" => $items->status,
                        ];
                           
                    }     
                    }     
                    $checklist[] = [
                            "id" => $checklists->id,
                            "id_veiculo" => $checklists->id_veiculo,
                            "data_chklist" => $checklists->data_chklist,
                            "tipo_chklist" => chkTipo($checklists->tipo_chklist),
                            "id_condutor" => $checklists->id_condutor,
                            "atendido" => $checklists->atendido,
                            "aceite" => $checklists->aceite,
                            "observacao" => $checklists->observacao,
                            "items_check"=> $itemsChk,                         
                         ];
                                                                       
                }
                elseif($pendencia->tipo == 'R')
                {
               
                    $vouc = DetalhesRequisicaoVeiculos::where('id_condutor',$pendencia->condutor_id)->where('id',$pendencia->id_origem)->first();
                    if($vouc!=null){
                        $voucher[] = [
                                        "id" => $vouc->id,
                                        "id_requisicao" => $vouc->id_requisicao,
                                        "categoria_veiculo" => $vouc->categoria_veiculo,
                                        "id_condutor" => $vouc->id_condutor,
                                        "data_retirada" => $vouc->data_retirada,
                                        "prazo_locacao" => $vouc->prazo_locacao,
                                        "data_devolucao" => $vouc->data_devolucao,
                                        "cidade_retirada" => $vouc->cidade_retirada,
                                        "estado_retirada" => $vouc->estado_retirada,
                                        "local_rodagem" => $vouc->local_rodagem,
                                        "km_mensal" => $vouc->km_mensal,
                                        "Limite_cartao_combustivel" => $vouc->Limite_cartao_combustivel,
                        ];
                    }
                }
                else
                {
                    
                    $detalhesVeiculo = DetalheVeiculo::where('id',$pendencia->id_origem)->first();
                  
                    if($detalhesVeiculo!=null){
                        $detalhe[] = [
                            "id" => $detalhesVeiculo->id,
                            "veiculo_id" => $detalhesVeiculo->veiculo_id,
                            "condutor_id" => $detalhesVeiculo->condutor_id,
                            "tipo" => $detalhesVeiculo->tipo,
                            "valor_inicial" => $detalhesVeiculo->valor_inicial,
                            "valor_final" => $detalhesVeiculo->valor_final,
                            "cadastro" => $detalhesVeiculo->cadastro,
                            "atualizado" => $detalhesVeiculo->atualizado,
                     ]; 
                    }
                   
                }

                
            }
            $pendencias['voucher'] = $voucher;
            $pendencias['checklist'] = $checklist;
            $pendencias['detalhes'] = $detalhe;            
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel executar a consulta de Pendências!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de pendências!',
            'status' => 'OK',
            'data' => $pendencias
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
        //
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
            
            $condutor = Condutores::where('id_usuario',$id)->first(['id']);
            $tmpPendencias = PendenciasVeiculos::where('condutor_id',$condutor->id)->where('status',0)->get();           
            
            $checklist = null;
            $detalhe = null;
            $voucher = null;
            $pendencias = null;
            foreach ($tmpPendencias as $pendencia) {  
                                     
                if($pendencia->tipo == 'C'){
                   
                    $checklists = Checklists::with('checklistVeiculo')->where('id',$pendencia->id_origem)->where('atendido','N')->first();
                    
                    if($checklists!=null){
                    $itemsChk = null;
                    
                    foreach($checklists->checklistVeiculo as $items)
                    {               
                        $itemsChk[] = [
                                        "id" => $items->id,
                                        "id_condutor" => $items->id_condutor,
                                        "id_veiculo" => $items->id_veiculo,
                                        "id_checklist" => $items->id_checklist,
                                        "id_checklistitem" => $items->id_checklistitem,
                                        "item_nome"=> ChecklistItems::where('id',$items->id_checklistitem)->first(['item'])->item,
                                        "status" => $items->status,
                        ];
                           
                    }     
                         
                    $checklist[] = [
                            "id" => $checklists->id,
                            "id_veiculo" => $checklists->id_veiculo,
                            "data_chklist" => $checklists->data_chklist,
                            "tipo_chklist" => chkTipo($checklists->tipo_chklist),
                            "id_condutor" => $checklists->id_condutor,
                            "atendido" => $checklists->atendido,
                            "aceite" => $checklists->aceite,
                            "observacao" => $checklists->observacao,
                            "items_check"=> $itemsChk,                         
                         ]; 
                    }
                                                         
                }
                elseif($pendencia->tipo == 'R')
                {
                   
                    $vouc = Voucher::where('id_condutor',$condutor->id)->where('id',$pendencia->id_origem)->first();
                  
                    if($vouc!=null)
                    {
                        $voucher[] = [
                            "id" => $vouc->id,
                            "id_requisicao" => $vouc->id_requisicao,
                            "categoria_veiculo" => $vouc->categoria_veiculo,
                            "id_condutor" => $vouc->id_condutor,
                            "data_retirada" => $vouc->data_retirada,
                            "prazo_locacao" => $vouc->prazo_locacao,
                            "data_devolucao" => $vouc->data_devolucao,
                            "cidade_retirada" => $vouc->cidade_retirada,
                            "estado_retirada" => $vouc->estado_retirada,
                            "local_rodagem" => $vouc->local_rodagem,
                            "km_mensal" => $vouc->km_mensal,
                            "Limite_cartao_combustivel" => $vouc->Limite_cartao_combustivel,
                        ];
                    }
                   

                    $checklists = Checklists::with('checklistVeiculo')->where('id',$pendencia->id_origem)->first();
                    $itemsChk = null;
                    
                    foreach($checklists->checklistVeiculo as $items)
                    {               
                        $itemsChk[] = [
                                        "id" => $items->id,
                                        "id_condutor" => $items->id_condutor,
                                        "id_veiculo" => $items->id_veiculo,
                                        "id_checklist" => $items->id_checklist,
                                        "id_checklistitem" => $items->id_checklistitem,
                                        "item_nome"=> ChecklistItems::where('id',$items->id_checklistitem)->first(['item'])->item,
                                        "status" => $items->status,
                        ];
                           
                    }     
                         
                    $checklist[] = [
                            "id" => $checklists->id,
                            "id_veiculo" => $checklists->id_veiculo,
                            "data_chklist" => $checklists->data_chklist,
                            "tipo_chklist" => chkTipo($checklists->tipo_chklist),
                            "id_condutor" => $checklists->id_condutor,
                            "atendido" => $checklists->atendido,
                            "aceite" => $checklists->aceite,
                            "observacao" => $checklists->observacao,
                            "items_check"=> $itemsChk,                         
                         ]; 
                }
                else
                {
                    $detalhesVeiculo = DetalheVeiculo::where('id',$pendencia->id_origem)->first();
                    $detalhe[] = [
                                    "id" => $detalhesVeiculo->id,
                                    "veiculo_id" => $detalhesVeiculo->veiculo_id,
                                    "condutor_id" => $detalhesVeiculo->condutor_id,
                                    "tipo" => $detalhesVeiculo->tipo,
                                    "valor_inicial" => $detalhesVeiculo->valor_inicial,
                                    "valor_final" => $detalhesVeiculo->valor_final,
                                    "cadastro" => $detalhesVeiculo->cadastro,
                                    "atualizado" => $detalhesVeiculo->atualizado,
                    ]; 
                }

                
            }
            $pendencias['voucher'] = $voucher;
            $pendencias['checklist'] = $checklist;
            $pendencias['detalhes'] = $detalhe;            
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel executar a consulta de Pendências!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de pendências!',
            'status' => 'OK',
            'data' => $pendencias
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
