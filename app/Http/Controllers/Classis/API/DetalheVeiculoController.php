<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Condutores;
use App\Models\DetalheVeiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;


class DetalheVeiculoController extends Controller
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

            $tmpDetalhes = DetalheVeiculo::with('veiculo')->get();             
            
            $detalhesVeiculo = null;

            foreach ($tmpDetalhes as $detalhes) {
                
                $condutor = Condutores::with('usuario')->where('id', $detalhes->condutor_id)->first();
                $detalhesVeiculo[] = [
                        'id' => $detalhes->id,
                        'condutor_id' => $detalhes->condutor_id,
                        'veiculo_id' => $detalhes->veiculo_id,
                        'nome_condutor' => $condutor->usuario->nome,
                        'tipo' => $detalhes->tipo,
                        "valor_inicial" => $detalhes->valor_inicial,
                        "valor_final" => $detalhes->valor_final,
                        "cadastro" => $detalhes->cadastro,
                        "atualizado" => $detalhes->atualizado
                ];
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel executar a consulta de Detalhes!',
                'code' => $e->getCode(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de Detahles!',
            'status' => 'OK',
            'data' => $detalhesVeiculo
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
                $dados['cadastro'] = Carbon::now()->format('Y-m-d');                
                $foto = $request->file('foto');               
                if($foto==null){
                    $foto_link = null;
                }
                else
                {
                    $destino = 'upload';
                    $fotoName = Carbon::now()->format('Ymdhis').'_'.$request->veiculo_id.'_'.$request->condutor_id.'.'.$foto->getClientOriginalExtension();
                    $foto_link = url('storage/upload/detalhes'.$fotoName);        
                    $foto->move($destino,$fotoName);
                    $dados['foto'] = $foto_link;
                } 
                $manutencao = DetalheVeiculo::where('veiculo_id', $request->veiculo_id)->where('condutor_id', $request->condutor_id)->where('tipo','Manutencao')->where('valor_final', null)->first();
                $oleo = DetalheVeiculo::where('veiculo_id', $request->veiculo_id)->where('condutor_id', $request->condutor_id)->where('tipo','Oleo')->where('valor_final', null)->first();
                $km = DetalheVeiculo::where('veiculo_id', $request->veiculo_id)->where('condutor_id', $request->condutor_id)->where('tipo','KM')->where('valor_final', null)->first();       
                if($km!=null && $km->valor_inicial > $request->valor_inicial){
                    return response()->json([
                        'message' => 'Valor de KM inserido deve ser Maior que o valor anterior',
                        'status' => 'ERROR'
                    ], 200);
                }

                if($manutencao!=null && $manutencao->valor_inicial > $request->valor_inicial){
                    return response()->json([
                        'message' => 'Valor de KM da Manutenção realizada inserido deve ser Maior que o valor anterior',
                        'status' => 'ERROR'
                    ], 200);
                }

                if($oleo!=null && $oleo->valor_inicial > $request->valor_inicial){
                    return response()->json([
                        'message' => 'Valor de KM da ultima Troca de Oleo inserido deve ser Maior que o valor anterior',
                        'status' => 'ERROR'
                    ], 200);
                }

                $detalhe = new DetalheVeiculo();
                $detalhe->create($dados);            
            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Erro ao gravar detalhe!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Erro ao gravar detalhe!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
            ], 404);
        } 

        return response()->json([
            'message' => 'Detalhe cadastrado com sucesso!',
            'status' => 'OK'
        ], 201);
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

            $detalhes = DetalheVeiculo::with('veiculo')->where('veiculo_id',$id)->get();
            
            $detalhesVeiculo = null;
            foreach($detalhes as $detalhe){
                $condutor = Condutores::with('usuario')->where('id', $detalhe->condutor_id)->first();
            
                $detalhesVeiculo[] = [
                            'id' => $detalhe->id,
                            'condutor_id' => $detalhe->condutor_id,
                            'veiculo_id' => $detalhe->veiculo_id,
                            'nome_condutor' => $condutor->usuario->nome,
                            'tipo' => $detalhe->tipo,
                            "valor_inicial" => $detalhe->valor_inicial,
                            "valor_final" => $detalhe->valor_final,
                            "cadastro" => $detalhe->cadastro,
                            "atualizado" => $detalhe->atualizado
                    ];
            }
            
           
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel executar a consulta de Detalhes!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Detalhe Encontrado!',
            'status' => 'OK',
            'data' => $detalhesVeiculo
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
