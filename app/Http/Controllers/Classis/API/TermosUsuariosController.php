<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Termos;
use App\Models\TermosUsuarios;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;


class TermosUsuariosController extends Controller
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
            $tmpTermos = Termos::where('status',0)->get();
            
            $termos = null;

            foreach ($tmpTermos as $termo) {
                    $termos[] = [
                        'id' => $termo->id,
                        'Termo' => $termo->Termo,
                        'data_criacao' => $termo->data_criacao,
                        'status' => $termo->status,                       
                    ];
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel executar a consulta de Termos Existentes!',
                'code' => $e->getCode(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de Termos Existentes!',
            'status' => 'OK',
            'data' => $termos
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
        \DB::beginTransaction();

        $aceite = new TermosUsuarios();
        $aceite->fill($request->all());
        $aceite->save();

        DB::commit();
    } catch (QueryException $e) {
        DB::rollback();

        return response()->json([
            'message' => 'Erro ao gravar aceite!',
            'code' => $e->getCode(),
            'status' => 'ERROR'
        ], 404);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'message' => 'Erro ao gravar aceite!',
            'code' => $e->getCode(),
            'status' => 'ERROR'
        ], 404);
    } 
    return response()->json([
        'message' => 'Aceite Realizado com Sucesso!',
        'status' => 'OK'
    ], 201);
}


    
    /**
     * Verificar a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verificar($idUsuario)
    {
        try {
           
            $termo = TermosUsuarios::where('status',1)->where('id_usuario',$idUsuario)->first(); 
                      
          
    } catch (\Exception $e) {
        return response()->json([
            'mensagem' => 'Usuário não aceitou o Termo!',
            'code' => $e->getCode(),
            'status' => 'ERROR',
        ], 400);
    }

    return response()->json([
        'message' => 'Usuário aceitou o termo',
        'status' => 'OK',        
    ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idTermo)
    {
        try {
           
                $termo = Termos::where('status',0)->where('id',$idTermo)->first(); 
                          
              
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel executar a consulta de Termos Existentes!',
                'code' => $e->getCode(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de Termos Existentes!',
            'status' => 'OK',
            'data' =>  $termo
        ], 200);
    }
    
}
