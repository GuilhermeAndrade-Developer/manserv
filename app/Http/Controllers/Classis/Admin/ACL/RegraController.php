<?php

namespace App\Http\Controllers\Classis\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Models\Regra;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RegraController extends Controller
{
    protected $repository;

    public function __construct(Regra $regra)
    {
        $this->repository = $regra;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $regras = $this->repository->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a consulta de regras!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Lista de Regras',
            'status' => 'OK',
            'data' => $regras
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {

            if (empty($request->all())) {
                return response()->json([
                    'message' => "Não há dados para serem gravados na regra!",
                    'status' => 'ERROR'
                ], 406);
            }

            $regra = $this->repository->where('nome', $request->nome)->first();

            if (isset($regra)) {
                return response()->json([
                    'message' => "Regra já cadastrada!",
                    'status' => 'ERROR'
                ], 404);
            }

            DB::beginTransaction();
            $regra = $this->repository->create($request->all());

            DB::commit();

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante o cadastro de regras!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante o cadastro de regras!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }
        return response()->json([
            'message' => "Regra cadastrada com sucesso!",
            'status' => 'OK',
            'data' => $regra
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $regra = $this->repository->find($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a consulta de regra!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Exibição da regra.',
            'status' => 'OK',
            'data' => $regra
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            if (empty($request->all())) {
                return response()->json([
                    'message' => "Não há dados para serem alterados na regra!",
                    'status' => 'ERROR'
                ], 406);
            }

            if (empty($this->repository->find($id))) {
                return response()->json([
                    'message' => "Regra não existe no banco de dados!",
                    'status' => 'ERROR'
                ], 404);
            }

            DB::beginTransaction();
            $regra = $this->repository->find($id);

            $regra->update($request->all());
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a atualização da regra!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a atualização da regra!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Regra atualizada com sucesso.',
            'status' => 'OK',
            'data' => $regra
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            if (empty($this->repository->find($id))) {
                return response()->json([
                    'message' => "Regra não existe no banco de dados!",
                    'status' => 'ERROR'
                ], 404);
            }

            DB::beginTransaction();
            $regra = $this->repository->find($id);

            $regra->delete();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a exclusão da regra!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a exclusão da regra!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Regra excluída com sucesso.',
            'status' => 'OK',
            'data' => $regra
        ], 200);
    }
}
