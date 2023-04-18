<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\AssociarUsuarioUT;
use App\Models\Usuarios;
use App\Models\UT;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AssociarUsuarioUtController extends Controller
{

    public function store(Request $request)
    {

        try {
          //  DB::beginTransaction();
            $request->all();
            $usuario_ut = new AssociarUsuarioUT();
            $usuario_ut->fill($request->all());
            $usuario_ut->save();

          //  DB::commit();
        } catch (QueryException $e) {
          //  DB::rollBack();

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
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */

    public function show(int $id)
    {
        try {
            $ut = UT::with('usuarios')->where('id', $id)->first();
            $usuario_ut = AssociarUsuarioUT::find('name')->where('id', $id)->first();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar usuário.',
                'code' => $e->getCode(),
                'error' => $e->getMessage(),
                'status' => 'ERROR'
            ], 404);
        }
        return response()->json([
            'message' => 'Usuário encontrado com sucesso!',
            'status' => 'OK',
            'data' => $usuario_ut->get()
        ], 200);
    }

}
