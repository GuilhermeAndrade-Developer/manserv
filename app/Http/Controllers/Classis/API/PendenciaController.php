<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Condutores;
use App\Models\Pendencias;
use App\Models\VouchersLocadoras;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class PendenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @ param int $id
     */
    public function index(int $id)
    {
        try {
            $tmpPendencias = Pendencias::with('condutor.usuario.UT')->where('condutor_id',$id)->get();
            $pendencias = null;
            $vouchers = null;
            foreach ($tmpPendencias as $pendencia) {            
               
                   
                    if($pendencia->tipo==1 || $pendencia->tipo==4){
                        $voucher = VouchersLocadoras::with('cidade_ret')->with('cidade_dev')->where('id_condutor',$id)->get()->last();
                        
                        if($pendencia->tipo==1){
                            $vouchers = [
                                'data'=>$voucher->data_retirada,
                                'hora'=>$voucher->hora_retirada,
                                'num_voucher'=>$voucher->numero,
                                'locadora'=>$voucher->locadora,
                                'local_retirada'=>['Endereco'=>$voucher->endereco_retirada,"Cidade"=>$voucher->cidade_ret->nome,"Estado"=>$voucher->cidade_ret->uf,"Referencia"=>$voucher->ref_retirada],
                            ];
                        }else{
                            $vouchers = [
                                'data'=>$voucher->data_devolucao,
                                'hora'=>$voucher->hora_devolucao,
                                'num_voucher'=>$voucher->numero,
                                'locadora'=>$voucher->locadora,
                                'local_retirada'=>['Endereco'=>$voucher->endereco_devolucao,"Cidade"=>$voucher->cidade_dev->nome,"Estado"=>$voucher->cidade_dev->uf,"Referencia"=>$voucher->ref_devolucao],
                            ];
                        }
                           
                        
                    }
                    $pendencias[] = [
                        'id' => $pendencia->id,
                        'tipo' => ['tipo_id'=>$pendencia->tipo,"descricao"=>pendenciaTipo($pendencia->tipo)],
                        'nome_condutor' => $pendencia->condutor->usuario->nome,
                        'UT'=> ['id'=>$pendencia->condutor->usuario->UT->id,'numero_ut'=>$pendencia->condutor->usuario->UT->numero_ut,'descricao'=>$pendencia->condutor->usuario->UT->descricao],
                        'abertura' => $pendencia->abertura,
                        'fechamento' => $pendencia->fechamento,
                        'status' => $pendencia->status,
                        'voucher'=>$vouchers
                    ];

            }
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
        //
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
