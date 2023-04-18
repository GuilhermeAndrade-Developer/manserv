<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprovacaoRequisicaoRequest;
use App\Models\RequisicaoVeiculos;
use App\Models\AprovacaoRequisicaoVeiculoUt;
use App\Models\GestoresUt;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AprovacaoRequisicaoVeiculoUtController extends Controller
{
    public function registrar(AprovacaoRequisicaoRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $requisicao = RequisicaoVeiculos::find($id);
            $aprovacao = $request->only(['indicador_aprovacao']);
            $observacao = $request->only(['observacao']);
            $aprovacao->save();

            if($aprovacao == 1) {
                return response()->json([
                    'message' => 'Requisição Aprovada!',
                    'status' => 'OK'
                ], 200);
            }else{
                return response()->json([
                    'message' => "O Campo observação deve ser preenchido!",
                    'status' => 'OK'
                ], 200);
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocorreu um erro ao atualizar a Requisição de Veículos!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocorreu um erro ao processar a atualização da Requisição de Veículos!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }
        return response()->json([
            'message' => 'Aprovação do Veículo atualizada com sucesso!',
            'status' => 'OK'
        ], 200);
    }

    public function lista()
    {   
        try {       
            $requisicoes = RequisicaoVeiculos::all();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao listar Requisições de Veículos!',
                'code' => $e->getCode(),
                'status' => 'Error'
            ], 404);
        }
            return response()->json([
                'message'=> 'Lista de Requisição realizada com sucesso!',
                'status'=> 'OK',
                'data' => $requisicoes
            ], 200);
    }

    public function show(int $id = 0, Request $request)
    {   
        try {
           
            if($id > 0)
            {
               $requisicoes = RequisicaoVeiculos::find($id)->get();
            }
            else
            {
               $filtro = $request->query();
               $requisicoes = RequisicaoVeiculos::where($filtro)->get();
            }           
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao listar Requisições de Veículos!',
                'code' => $e->getCode(),
                'status' => 'Error'
            ], 404);
        }
            return response()->json([
                'message'=> 'Lista de Requisição realizada com sucesso!',
                'status'=> 'OK',
                'data' => $requisicoes
            ], 200);
    }


    public function lista_usuario(int $id, bool $requisitante=false)
    {   
        try {      
            
            $usuario = Usuarios::with('gestors')->where('id',$id)->first();
            $tipo_gestor = null;
            $tipo_gestor_status = null;
            $utsControle = null;
            $uts = null;
            $representante = null;
            $gestor = null;
            if($usuario->perfil == 4 || $usuario->perfil == 6 || $usuario->perfil == 9){
                $utsControle = null;
                $requisicao = RequisicaoVeiculos::where('status', $usuario->perfil)->get();
                
            }else{

            if($usuario->isRepresentative($id)){
                $representante = $usuario->hasRepresentative($id);
                foreach($representante as $ut)
                {                   
                   if($uts == null){
                        $uts[] = $ut['id'];
                        $utsControle[] = ['Tipo'=>'Representante','id_ut'=>$ut['id'],'numero_ut'=>$ut['numero_ut']];
                    }
                    elseif(!in_array($ut['id'],$uts))
                    {
                        $uts[] = $ut['id'];
                        $utsControle[] = ['Tipo'=>'Representante','id_ut'=>$ut['id'],'numero_ut'=>$ut['numero_ut']];
                    }
                }
            } 
                      
            if($usuario->isManager($id)){                
                $gestor = $usuario->gestor;    
                        
                foreach($gestor as $ut)
                {
                   
                   if($uts == null){
                        $uts[] = $ut->id_ut_cc;
                        $utsControle[] = ['Tipo'=>tipoGestor($ut->tipo_gestor),'id_ut'=>$ut->id_ut_cc,'numero_ut'=>$ut->ut->numero_ut];
                        $tipo_gestor[] = tipoGestor($ut->tipo_gestor);
                        $tipo_gestor_status[] = tipoGestorStatus($ut->tipo_gestor);
                    }
                    elseif(!in_array($ut->id_ut_cc,$uts))
                    {
                        $uts[] = $ut->id_ut_cc;
                        $utsControle[] = ['Tipo'=>tipoGestor($ut->tipo_gestor),'id_ut'=>$ut->id_ut_cc,'numero_ut'=>$ut->ut->numero_ut];
                        $tipo_gestor[] = tipoGestor($ut->tipo_gestor);
                        $tipo_gestor_status[] = tipoGestorStatus($ut->tipo_gestor);
                    }
                }               
            }

            if($usuario->id_ut_cc!=null){
                if($uts == null){
                    $uts[] = $usuario->id_ut_cc;
                    $utsControle[] = ['Tipo'=>tipoGestor($usuario->gestor[0]->tipo_gestor),'id_ut'=>$usuario->id_ut_cc,'numero_ut'=>$usuario->ut_cc];
                }
                elseif(!in_array($usuario->id_ut_cc,$uts))
                {
                    $uts[] = $usuario->id_ut_cc;
                    $utsControle[] = ['Tipo'=>tipoGestor($usuario->gestor[0]->tipo_gestor),'id_ut'=>$usuario->id_ut_cc,'numero_ut'=>$usuario->ut_cc];
                }  
                
            } 
            if($requisitante){
               $requisicao = RequisicaoVeiculos::whereIn('id_ut_cc',$uts)->where('id_requisitante',$id)->get();
            }
            else
            {       
               $requisicao = RequisicaoVeiculos::whereIn('id_ut_cc',$uts)->whereIn('status',$tipo_gestor_status)->get();
            }              
            
            }

            $requisicoes ['requisicoes'] = $requisicao;
            $requisicoes ['Uts'] = $utsControle;
          
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao listar as Requisições de Veículos!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'Error'
            ], 404);
        }
            return response()->json([
                'message'=> 'Lista de Requisição realizada com sucesso!',
                'status'=> 'OK',
                'data' => $requisicoes
            ], 200);
    }
    public function reprovar(Request $request)
    {   
        try {
            DB::beginTransaction();
            $gestores = GestoresUt::where('id_ut_cc',$request->id_ut_cc)->where('id_gestor',$request->id_usuario)->first();
            if($request->id==null)
                    {
                        return response()->json([
                            'message' => 'Não existe o Id da Requisição!',
                            'status' => 'Error'
                        ], 406);
                    }
            
            $requisicao = RequisicaoVeiculos::where('id',$request->id)->first();
            if($gestores==null && $requisicao->status!=7){
                return response()->json([
                    'message' => 'Apenas Usuarios Gestores Podem Reprovar Requisições!',
                    'status' => 'Error'
                ], 406);
            }

            if($request->justificativa==null)
                    {
                        return response()->json([
                            'message' => 'A justificativa não pode ser em branco ou Nula!',
                            'status' => 'Error'
                        ], 406);
                    }
            
            $tipo = ($gestores==null)? "F":$gestores->tipo_gestor;
                       
            $requisicaoAprovacao = new AprovacaoRequisicaoVeiculoUt();
            $reprovar = [
                "id_num_requisicao"=>$requisicao->id,
                "data_aprovacao"=>Carbon::now()->toDateString(),
                "id_ut_cc"=>$requisicao->id_ut_cc,                           
                "tipo_gestor"=>$tipo,
                "id_gestor"=>$request->id_usuario,
                "observacao"=>$request->justificativa,
                "status"=>1
            ];
            
            $requisicaoAprovacao->create($reprovar);
   
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocorreu um erro ao Reprovar a Requisição de Veículos!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
            ], 406);
        }  catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao Reprovar a Requisição de Veículos!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'Error'
            ], 406);
        }
            return response()->json([
                'message'=> 'Requisição Reprovada!',
                'status'=> 'OK'
            ], 200);
    }

    public function aprovar(Request $request)
    {
        try {
            DB::beginTransaction();

            $gestores = GestoresUt::where('id_ut_cc',$request->id_ut_cc)->where('id_gestor',$request->id_usuario)->first();
            $requisicao = RequisicaoVeiculos::where('id',$request->id)->first();
            
            if($request->id==null)
            {
                return response()->json([
                            'message' => 'Não existe o Id da Requisição!',
                            'status' => 'Error'
                ], 406);
            }

            if($gestores==null && $requisicao->status!=7){
                return response()->json([
                    'message' => 'Apenas Usuarios Gestores Podem Aprovar Requisições!',
                    'status' => 'Error'
                ], 406);
            }
            
            $tipo = ($gestores==null)? "F":$gestores->tipo_gestor;
            
            $requisicaoAprovacao = new AprovacaoRequisicaoVeiculoUt();
                        
            $aprovar = [
                            "id_num_requisicao"=> $requisicao->id,
                            "data_aprovacao"=> Carbon::now()->toDateString(),
                            "id_ut_cc"=> $requisicao->id_ut_cc,                           
                            "tipo_gestor"=> $tipo,
                            "id_gestor"=> $request->id_usuario,
                            "observacao"=> $request->observacao,
                            "status"=> 1                         
                        ];
                       
            $requisicaoAprovacao->create($aprovar);
            
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocorreu um erro ao Aprovar a requisição de veículo!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
            ], 406);
        }  catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao Aprovar Requisições de Veículos',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'Error'
            ], 406);
        }
            return response()->json([
                'message'=> 'Requisição Aprovada!',
                'status'=> 'OK'
            ], 200);
    }
    
}