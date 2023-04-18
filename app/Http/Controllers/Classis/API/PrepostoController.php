<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Preposto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PrepostoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $preposto = Preposto::all();

        return response()->json([
            'message' => 'Listagem processada com sucesso!',
            'status' => 'OK',
            'data' => $preposto
        ], 200);
        
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param Request $request
    * @return JsonResponse
    */

    public function store(Request $request)
    {

        try{
            DB::beginTransaction();

            $preposto = new Preposto();
            $preposto->fill($request->all());
            $preposto->save();

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ooops! Não foi possível efetivar sua solicitação!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
                ], 406);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ooops! Erro ao processar preposto',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Preposto cadastrado com sucesso!',
            'status' => 'OK',
            'data' => $preposto
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        try {
            $preposto = Preposto::find($id);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao recuperar o preposto!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao processar o preposto!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }
        return response()->json([
            'message' => 'Preposto recuperado com sucesso!',
            'status' => 'OK',
            'data' => $preposto->get()
        ], 200);
    }

        /**
     * Update the specified resource in storage.
     *
     * @param PrepostoRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PrepostoRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $preposto = Preposto::find($id);
            $preposto->fill($request->all());
            $preposto->save();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocorreu um erro ao atualizar o Preposto!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocorreu um erro ao processar a atualização do Preposto!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }
        return response()->json([
            'message' => 'Preposto atualizado com sucesso!',
            'status' => 'OK'
        ], 200);
    }

}