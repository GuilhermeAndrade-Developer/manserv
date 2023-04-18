<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\UsuariosRepresentantes;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UsuarioRepresentanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function lista()
    {
        $representantes = UsuariosRepresentantes::with('usuario')->with('ut')->get();

        return response()->json([
            'message' => 'Listagem de usuários representantes',
            'status' => 'OK',
            'data' => $representantes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function gravar(Request $request)
    {
        try {
            DB::beginTransaction();
            $dados = $request->all();
            $dados['status'] = ($dados['status'])? 1:0;
            $representante = new UsuariosRepresentantes();
            $representante->fill($dados);
            $representante->save();

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ocorreu um erro durante a associação do usuário!',
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Associação de usuário efetuada com sucesso!',
            'status' => 'OK'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function exibir(int $id)
    {
        try {
            $representante = UsuariosRepresentantes::find($id);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Não foi possível remover a associação de usuário à UT.',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }
        return response()->json([
            'message' => 'Associação de usuário removida com sucesso!',
            'status' => 'OK',
            'data' => $representante->get()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function apagar(int $id)
    {
        try {
            DB::beginTransaction();

            $representante = UsuariosRepresentantes::find($id);
            $representante->delete();

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Não foi possível remover a associação de usuário à UT.',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Associação de usuário removida com sucesso!',
            'status' => 'OK'
        ], 200);
    }
}
