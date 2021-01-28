<?php

namespace App\Http\Controllers\Classis\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use App\Models\Permissao;
use App\Models\Regra;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissaoController extends Controller
{
    protected $repository;
    protected $regra;
    protected $perfil;

    /**
     * PermissaoController constructor.
     */
    public function __construct(Permissao $permissao, Regra $regra, Perfil $perfil)
    {
        $this->repository = $permissao;
        $this->regra = $regra;
        $this->perfil = $perfil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $permissoes = $this->repository->with('regras')->with('perfil')->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Ooops, ocorreu um erro durante a consulta de permissões!",
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Lista de Permissões',
            'status' => 'OK',
            'data' => $permissoes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        foreach ($request->all() as $validate) {
            $validateData = Validator::make($validate, [
                'regra' => ['required', 'int'],
                'perfil' => ['required', 'int'],
                'acesso' => ['required', 'int', 'max:1'],
            ])->validate();
        };

        foreach ($request->all() as $temp) {
            $permissaoTemp = $this->repository->where('perfil', $temp['perfil'])
                ->where('regra', $temp['regra'])
                ->where('acesso', $temp['acesso'])
                ->first();

            if (isset($permissaoTemp)) {
                return response()->json([
                    'message' => 'Não é possível registrar uma permissão já existente.',
                    'status' => 'ERROR'
                ], 406);
            }
        }

        try {
            $permissao = [];
            DB::beginTransaction();

            foreach ($request->all() as $tempPermissao) {
                $permissao[] = $this->repository->create($tempPermissao);
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ooops, ocorreu um erro ao gravar no banco de dados!.',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 406);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ooops, ocorreu um problema durante a execução!.',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 400);
        }

        return response()->json([
            'message' => 'Permissões gravadas com sucesso!',
            'status' => 'OK',
            'data' => $permissao
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $perfilId
     * @return JsonResponse
     */
    public function show(int $perfilId)
    {
        $permissao = $this->repository->where('perfil', $perfilId)->get();

        return response()->json([
            'message' => 'Lista de permissões.',
            'status' => 'OK',
            'data' => $permissao
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        foreach ($request->all() as $validate) {
            $validateData = Validator::make($validate, [
                'regra' => ['required', 'int'],
                'perfil' => ['required', 'int'],
                'acesso' => ['required', 'int', 'max:1'],
            ])->validate();
        };

        try {
            DB::beginTransaction();
            foreach ($request->all() as $temp) {
                $this->repository->where('perfil', $id)
                    ->where('regra', $temp['regra'])
                    ->first()
                    ->update(['acesso' => $temp['acesso']]);
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ooops, ocorreu um erro ao gravar no banco de dados!.',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 406);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ooops, ocorreu um problema durante a execução!.',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Permissões alteradas com sucesso!',
            'status' => 'OK',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $permissoes = $this->repository->where('perfil', $id)->get();
        try {
            DB::beginTransaction();
            foreach ($permissoes as $temp) {
                $this->repository->where('id', $temp->id)
                    ->first()
                    ->delete();
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ooops, ocorreu um erro ao gravar no banco de dados!.',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 406);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ooops, ocorreu um problema durante a execução!.',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Permissões excluídas com sucesso!',
            'status' => 'OK',
        ], 200);
    }

    /**
     * Adiciona uma regra disponível à permissão.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function rulesAvailable(int $id)
    {
        $perfil = Perfil::find($id);

        if (! isset($perfil)) {
            return response()->json([
                'message' => 'Ooops, não encontramos o perfil solicitado!',
                'status' => 'ERROR'
            ], 404);
        }

        $regras = $this->perfil->rulesAvailable($id);

        if (empty($regras)) {
            return response()->json([
                'message' => "Não há regras disponíveis para o perfil '{$perfil->nome}'!",
                'status' => 'OK',
                'data' => $regras
            ], 200);
        }

        return response()->json([
            'message' => "Lista de regras disponíveis para o perfil '{$perfil->nome}'!",
            'status' => 'OK',
            'data' => $regras
        ], 200);
    }

    public function profilesAvailable(int $id)
    {
        $regra = Regra::find($id);

        if (! isset($regra)) {
            return response()->json([
                'message' => 'Ooops, não encontramos a regra solicitada!',
                'status' => 'ERROR'
            ], 404);
        }

        $perfis = $this->regra->profilesAvailable($id);

        if (empty($perfis)) {
            return response()->json([
                'message' => "Não há perfil disponível para a regra '{$regra->nome}'!",
                'status' => 'OK',
                'data' => $perfis
            ], 200);
        }

        return response()->json([
            'message' => "Lista de perfis disponíveis para a regra '{$regra->nome}'!",
            'status' => 'OK',
            'data' => $perfis
        ], 200);
    }
}
