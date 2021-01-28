<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetalhesRequisicaoVeiculos;
use App\Models\Condutores;
use App\Models\Pendencias;
use App\Models\UT;
use Illuminate\Support\Facades\DB;




class DetalhesRequisicaoVeiculosController extends Controller
{
    public function show($id)
    {
        $detalhes_requisicao = DetalhesRequisicaoVeiculos::with('requisicao')
        ->with('cidade_ret')
        ->with('voucher.veiculo')
        ->where('id', $id)
        ->get()
        ->first()->toArray();
        
        $data = [];
        
        foreach($detalhes_requisicao as $atributo => $detalhe){
            if($atributo === 'id_condutor'){
                $data['condutor'] = $this->montarCondutor($detalhe);
                continue;
            }
            /** 
             * @todo (Adamo) - verificar nome do relacionamento do model para
             * deixar compativel com o nome do atributo
             * 
             * @todo Verificar com vagner: Não há cidade de devolução na
             * tabela detalhes_requisicao_veiculos
             */
            if($atributo === 'cidade_retirada') {
                continue;
            } else if($atributo === 'cidade_ret') {
                $atributo = 'cidade_retirada';
            }

            $data[$atributo] =  $detalhe;
            
            if($atributo === 'requisicao') {
                $ut = $this->montarUt($detalhe['id_ut_cc']);
                $data['requisicao']['ut'] = $ut;
            }
            
        }
        
        return response()->json([
            'message' => 'Listagem processada com sucesso!',
            'status' => 'OK',
            'data' => $data
        ], 200);
    }
    /**
     * -------------------------------------------------------------------------------
     * Undocumented function
     *
     * @param [type] $id
     * @param [type] $status
     * @return void
     */
    public function atualizarStatus($id, $status) {
        try{
            DB::beginTransaction();
            $detalhe_veiculo = DetalhesRequisicaoVeiculos::where('id', $id)
            ->with('voucher.veiculo')->first();
            $detalhe_veiculo->status_veiculo = $status;
            
            $pendencia = [
                'condutor_id' => $detalhe_veiculo->id_condutor,
                'tipo' => 'A',
                'abertura' => $detalhe_veiculo->data_retirada,
                'fechamento' => null,
                'status' => '1'
            ];
            // dd($pendencia);
            $detalhe_veiculo->save();

            Pendencias::create($pendencia);
            DB::commit(); 
            return response()->json([
                'message' => 'Retirada do Veículo Autorizada com Sucesso',
                'status' => 'OK'
            ], 200);
        } catch (QueryException $e) {
                DB::rollback();
    
                return response()->json([
                    'message' => 'Erro ao Gerar Pendencias de Veiculo',
                    'code' => $e->getCode(),
                    'status' => 'ERROR'
                ], 500);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Erro ao Gerar Pendencias de Veiculo',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 500);
        }

    }

    /**
     * -----------------------------------------------------------------
     */
    private function montarCondutor($id_condutor) {
        $condutor = Condutores::with('usuario')
        ->where('id', $id_condutor)
        ->get();
        $data = [];
        foreach($condutor as $atributo => $dados) {
            if($atributo === 'usuario') {
                return $condutor;
                $data['nome'] = $dados['nome'];
                $data['cpf'] = $dados['cpf'];
                continue;
            }
            
            $data[$atributo] = $dados;
            
        }
        
        return $data[0];
    }
    /**
     * -------------------------------------------------------------------
     */
    private function montarUt($id_ut_cc) 
    {
        $ut = UT::find($id_ut_cc)->toArray();
        foreach($ut as $atributo => $dado) {
            $dados[$atributo] = $dado;
        }
        return $dados;
    }

}