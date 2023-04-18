<?php

namespace App\Http\Controllers\Classis\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    protected $repository;

    public function __construct(Perfil $perfil)
    {
        $this->repository = $perfil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $perfis = $this->repository->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a consulta de perfis!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Lista de Perfis',
            'status' => 'OK',
            'data' => $perfis
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {

            if (empty($request->all())) {
                return response()->json([
                    'message' => "Não há dados para serem gravados no perfil!",
                    'status' => 'ERROR'
                ], 406);
            }

            $perfil = $this->repository->where('nome', $request->nome)->first();

            if (isset($perfil)) {
                return response()->json([
                    'message' => "Perfil já cadastrado!",
                    'status' => 'ERROR'
                ], 404);
            }

            DB::beginTransaction();
            $perfil = $this->repository->create($request->all());

            DB::commit();

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a consulta de perfis!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a consulta de perfis!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }
        return response()->json([
            'message' => "Perfil cadastrado com sucesso!",
            'status' => 'OK',
            'data' => $perfil
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
            $perfil = $this->repository->find($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a consulta do perfil!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Exibição do perfil.',
            'status' => 'OK',
            'data' => $perfil
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            if (empty($request->all())) {
                return response()->json([
                    'message' => "Não há dados para serem alterados no perfil!",
                    'status' => 'ERROR'
                ], 406);
            }

            if (empty($this->repository->find($id))) {
                return response()->json([
                    'message' => "Perfil não existe no banco de dados!",
                    'status' => 'ERROR'
                ], 404);
            }

            DB::beginTransaction();
            $perfil = $this->repository->find($id);

            $perfil->update($request->all());
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a atualização do perfil!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a atualização do perfil!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'status' => 'OK',
            'data' => $perfil
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
                    'message' => "Perfil não existe no banco de dados!",
                    'status' => 'ERROR'
                ], 404);
            }

            DB::beginTransaction();
            $perfil = $this->repository->find($id);

            $perfil->delete();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a exclusão do perfil!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a exclusão do perfil!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Perfil excluído com sucesso.',
            'status' => 'OK',
            'data' => $perfil
        ], 200);
    }
}
