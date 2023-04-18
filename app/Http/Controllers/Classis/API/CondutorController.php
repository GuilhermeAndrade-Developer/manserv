<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CondutorRequest;
use App\Models\Condutores;
use App\Models\Checklists;
use App\Models\Veiculos;
use App\Models\DetalhesRequisicaoVeiculos;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CondutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function lista_condutor() {

        try {
            
            $manserv_condutores = Condutores::with('usuario')->where('status', 'A')->get();
            $condutores = [];
            foreach ($manserv_condutores as $condutor) {
                    $cnh = preg_match('/[0-9]{3}/', $condutor->cnh) ? $condutor->cnh : null;
                    $condutores [] = [
                        'id' => $condutor->id,
                        'id_usuario' => $condutor->id_usuario,
                        'nome' => $condutor->usuario->nome,
                        'numero_ut' => '9.'.$condutor->usuario->ut_cc,
                        'cnh' => $condutor->cnh,
                        'categoria_cnh' => $condutor->categoria_cnh,
                        'data_vencimento_cnh' => $condutor->data_vencimento_cnh,
                        'rg' => $condutor->rg,
                        'status' => $condutor->status,
                    ];
                }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar Condutores', 
                'code' => $e->getCode(),
                'Mensagem' => $e->getMessage(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de condutores!',
            'status' => 'OK',
            'condutores' => $condutores
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CondutorRequest  $request
     * @return JsonResponse
     */
    public function registrar_condutor(CondutorRequest $request)
    {
        try {
            $consulta_condutor = Condutores::where('id_usuario', $request->id_usuario)->first();
            $data_vencimento_cnh = $request->only(['data_vencimento_cnh']);
            $verificar_cnh = Condutores::where('cnh',$request->cnh)->first();
            $data_expiracao = Carbon::now()->subDays(30)->toDateString();

            if (isset($consulta_condutor)) {
                return response()->json([
                    'message' => 'Não foi possível cadastrar o condutor, o mesmo já está cadastrado!',
                    'status' => 'ERROR'
                ], 406);
            }

            if (isset($verificar_cnh)) {
                return response()->json([
                    'message' => 'Não foi possível cadastrar o condutor, a cnh informada está cadastrada!',
                    'status' => 'ERROR'
                ], 406);
            }           

            if ($data_vencimento_cnh < $data_expiracao ) {
                return response()->json([
                    'message' => 'Não foi possível cadastrar o condutor, data de vencimento de CNH expirou a mais 30 dias!',
                    'status' => 'Error'
                ], 406);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ooops! Ocorreu um erro durante o cadastro do condutor!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 400);

        }

        try {
            DB::beginTransaction();

            $novo_condutor = new Condutores();
            $novo_condutor->fill($request->all());
            $novo_condutor->save();

            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Erro ao gravar condutor!',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Erro ao gravar condutor!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }
        return response()->json([
            'message' => 'Condutor cadastrado com sucesso!',
            'status' => 'OK'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function mostrar_condutor(int $id)
    {
        try {
            $manserv_condutor = Condutores::with('usuario')->where('id', $id)->first();
            $condutor = $manserv_condutor;
            // dd($condutor);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar condutor',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 401);
        }

        if (!isset($condutor)) {
            return response()->json([
                'message' => 'Condutor não localizado!',
                'status' => 'OK'
            ], 200);
        }

        return response()->json([
            'message' => 'Consulta realizada com sucesso!',
            'status' => 'OK',
            'data' => $condutor
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CondutorRequest  $request
     * @return JsonResponse
     */
    public function atualizar_condutor(Request $request)
    {
        try {
            DB::beginTransaction();
            $condutor = Condutores::where('id_usuario', $request->id_usuario)->first();

            if (!isset($condutor)) {
                return response()->json([
                    'message' => 'O condutor indicado não foi localizado!',
                    'status' => 'ERROR'
                ], 404);
            }


            $condutor->data_vencimento_cnh = $request->data_vencimento_cnh;
            $condutor->categoria_cnh = $request->categoria_cnh;
            $condutor->rg = $request->rg;
            $condutor->save();

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ocorreu um erro durante a atualização do condutor!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ocorreu um erro durante o processamento da atualização do condutor!',
                'code' => $e->getCode() . " - Mensagem: " . $e->getMessage(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Condutor atualizado com sucesso!',
            'status' => 'OK'
        ], 201);
    }
    /**
     * Remover the specified resource in storage.
     *
     * @param  CondutorRequest  $request
     * @param int $id
     * @return JsonResponse
     */
    public function remover_condutor(int $id)
    {
        try {
            DB::beginTransaction();
            $condutor = Condutores::where('id', $id)->first();

            if (!isset($condutor)) {
                return response()->json([
                    'message' => 'O condutor indicado não foi localizado!',
                    'status' => 'ERROR'
                ], 404);
            }

            $condutor->fill(['status'=>'E']);
            $condutor->save();

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ocorreu um erro durante a remoção do condutor!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ocorreu um erro durante o processamento da remoção do condutor!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Condutor removido com sucesso!',
            'status' => 'OK'
        ], 201);
    }

    /**
     * @param Request
     * @return JsonResponse
     */
    public function condutoresPorUT(int $ut)
    {
        $usuarios = Usuarios::with('condutor')->with('ut')->where('id_ut_cc', $ut)->get();
        $condutores = [];
        foreach ($usuarios as $usuario) {
            
            // dd($usuarios);
            if (!empty($usuario->condutor)) {
                $condutor['id'] = $usuario->condutor->id;
                $condutor['id_usuario'] = $usuario->condutor->id_usuario;
                $condutor['nome'] = $usuario->nome;
                $condutor['cpf'] = $usuario->cpf;
                $condutor['cnh'] = $usuario->condutor->cnh;
                $condutor['categoria_cnh'] = $usuario->condutor->categoria_cnh;
                $condutor['data_vencimento_cnh'] = $usuario->condutor->data_vencimento_cnh;

                array_push($condutores, $condutor);
            }
        }

        if (count($condutores) == 0) {
            return response()->json([
                'message' => 'Não há condutores cadastrados para esta UT!',
                'status' => 'OK',
                'condutores' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Lista de Condutores',
            'status' => 'OK',
            'condutores' => $condutores
        ], 200);
    }
     /** Verificar Veiculo.
     *
     * @param  CondutorRequest  $request
     * @param int $id
     * @return JsonResponse
     */
    public function verificar_veiculos(int $id)
    {
        try {
            $data = Carbon::now()->subDays(30)->format('Y-m-d');
            
            $condutor = Condutores::where('id',$id)->where('data_vencimento_cnh','>=',$data)->first();
          
            if($condutor==null)
            {
                return response()->json([
                    'message' => 'Condutor não Cadastrado e ou Documentação de Condutor Vencida',
                    'code' => 201,
                    'status' => 'OK'
                ], 201);
            } 
           
                     
           $checklist = Checklists::where('id_condutor',$condutor->id)
           ->where(function ($query) {
                                        $query->where('tipo_chklist', 1)
                                            ->orWhere('tipo_chklist', 2);
                                        })->first();
                                                           
                       
           if($checklist==null){
            return response()->json([
                'message' => 'Veiculo Indisponivel',
                'code' => 201,
                'status' => "OK"
            ], 200);
           }  

           $checklists = [
               'Data' => $checklist->data_chklist,
               'Tipo' =>  ["numero"=>$checklist->tipo_chklist,"texto"=>pendenciaTipo($checklist->tipo_chklist)],
               'Status'=> ($checklist->checklist=="{}")? "Em aberto":"Executado",
           ];
                    
           $veiculo = Veiculos::with('detalhe')->with('ut')->with('modelo.marca')->with('categoria')->where('placa',$checklist['placa'])->first();
          
           $ut = null;
           $modelo = null;
           $categoria = null;
           $detalhe = null;

           if($veiculo->ut!=null){
                $ut = ['id'=>$veiculo->ut->id,'numero'=>$veiculo->ut->numero_ut,'descricao'=>$veiculo->descricao];
           }

           if($veiculo->modelo!=null){
                $modelo = ['id'=>$veiculo->modelo->id,'nome'=>$veiculo->modelo->nome,'marca'=>['id'=>$veiculo->modelo->marca->id,'nome'=>$veiculo->modelo->marca->nome]];
           }

           if($veiculo->categoria!=null){
            $categoria = ['id'=>$veiculo->categoria->id,'nome'=>$veiculo->categoria->nome];
           }



           $detalhes = $veiculo->detalhe;
           foreach($detalhes as $dt){
               if($dt->valor_final==null)
               {
                   
                $detalhe[] = [
                        "Tipo"=>$dt->tipo,
                        "Valor_Atual"=>$dt->valor_inicial,
                        "Data_Cadastro"=>$dt->cadastro,
                    ];
                }
           }
           
           $veiculos = [
               'id'=>$veiculo['id'],
               'placa'=>$veiculo['placa'],
               'categoria'=>$categoria,
               'combustivel'=>$veiculo['combustivel'],
               'licenciamento'=>$veiculo['combustivel'],
               'ano_modelo'=>$veiculo['ano_modelo'],
               'modelo'=>$modelo,               
               'ut'=>$ut,
               'checklists'=>$checklists,
               'detalhes'=>$detalhe
           ];

          
          
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Não existe Veiculo Associado ao Condutor',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Condutor possui 1 ou mais veiculos associados!',
            'status' => 'OK',
            'data'=>$veiculos
        ], 201);
    }

    public function retornarUsuariosCondutor(Request $request, $usuario, $ut = null) 
    {
        try {
            // $manserv_condutores = Condutores::with('usuario')->where('status', 'A')->get();
            $usuario_autenticado = Usuarios::find($usuario);
            // dd($usuario_autenticado);
            if($ut){
                $id_uts[] = $ut;
            } else {
                if($uts = $usuario_autenticado->hasManager($usuario)){
                    $id_uts = array_column($uts, 'id');
                }else if($uts = $usuario->hasRepresentative($usuario)) {
                    $id_uts = array_column($uts, 'id');
                }
            }
            
            $manserv_condutores = Condutores::select(
                'Condutores.*', 
                'Usuarios.cpf', 
                'Usuarios.nome', 
                'Usuarios.ut_cc', 
                'Usuarios.id as id_user',
                'Usuarios.status as status_usuario'
                )
                ->rightJoin('Usuarios', 'Condutores.id_usuario', 'Usuarios.id')
                ->whereIn('Usuarios.id_ut_cc',  $id_uts)
                ->get();
                
                // dd($ut);
            $condutores = [];
            foreach ($manserv_condutores as $condutor) {
                    $cnh = preg_match('/[0-9]{3}/', $condutor->cnh) ? $condutor->cnh : null;
                    $condutores [] = [
                        'id' => $condutor->id,
                        'id_usuario' => $condutor->id_user,
                        'cpf' => $condutor->cpf,
                        'nome' => $condutor->nome,
                        'numero_ut' => '9.'.$condutor->ut_cc,
                        'cnh' => $cnh,
                        'categoria_cnh' => $condutor->categoria_cnh,
                        'data_vencimento_cnh' => $condutor->data_vencimento_cnh,
                        'rg' => $condutor->rg,
                        'status' => $condutor->status_usuario,
                    ];
                }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar Condutores', 
                'code' => $e->getCode(),
                'Mensagem' => $e->getMessage(),
                'status' => 'ERROR',
            ], 400);
        }
        
        return response()->json([
            'message' => 'Listagem de condutores!',
            'status' => 'OK',
            'condutores' => $condutores
        ], 200);
    }
    public function usuario_condutor($id) 
    {
        try {
            $usuario = Usuarios::with('condutor')->where('id',$id)->get();
            $condutor = [
                    'id_condutor'=>$usuario->condutor->id,
                    'cnh'=>$usuario->condutor->cnh,
                    'categoria'=>$usuario->condutor->categoria_cnh,
                    'vencimento'=>$usuario->condutor->data_vencimento_cnh
            ];

        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar Condutores', 
                'code' => $e->getCode(),
                'Mensagem' => $e->getMessage(),
                'status' => 'ERROR',
            ], 400);
        }
        
        return response()->json([
            'message' => 'Listagem de condutores!',
            'status' => 'OK',
            'data' => $condutor
        ], 200);
    }
}