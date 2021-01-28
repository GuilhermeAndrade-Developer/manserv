<?php

namespace App\Http\Controllers\Classis\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Models\Permissao;
use App\Models\PermissaoCustom;
use App\Models\Usuarios;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissaoCustomController extends Controller
{
    protected $repository;

    public function __construct(PermissaoCustom $permissaoCustom)
    {
        $this->repository = $permissaoCustom;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $permissao = $this->repository->all();

            if ($permissao->count() == 0) {
                return response()->json([
                    'message' => 'Não há nenhuma Permissão Customizada cadastrada!',
                    'status' => 'OK'
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ooops, ocorreu um erro ao recuperar as permissões customizadas!!',
                'status' => 'ERRO',
                'code' => $e->getCode(),
            ], 400);
        }

        return response()->json([
            'message' => 'Lista de Permissões Customizadas!',
            'status' => 'OK',
            'data' => $permissao
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
        foreach ($request->all() as $validate) {
            $validateData = Validator::make($validate, [
                'usuario_id' => ['required', 'int'],
                'regra' => ['required', 'int'],
                'acesso' => ['required', 'int', 'max:1'],
            ])->validate();
        };

        if ($this->verificaDuplicidadePermissoes($request)) {
            return response()->json([
                'message' => 'Não é possível registrar permissões customizadas duplicadas para este usuário.',
                'status' => 'ERROR'
            ], 406);
        }

        if ($this->comparaPermissao($request)) {
            return response()->json([
                'message' => 'Não é possível registrar uma permissão customizada exatamente igual a original.',
                'status' => 'ERROR'
            ], 406);
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
                'message' => 'Ooops, ocorreu um erro ao acessar/gravar no banco de dados!.',
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
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $permissaoCustom = $this->repository->where('usuario_id', $id)->get();

        if ($permissaoCustom->count() == 0) {
            return response()->json([
                'message' => 'Não há permissões customizadas para o usuário selecionado!',
                'status' => 'OK'
            ], 200);
        }

        return response()->json([
            'message' => 'Lista de permissões customizdas do usuário atual.',
            'status' => 'OK',
            'data' => $permissaoCustom
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
        foreach ($request->all() as $validate) {
            $validateData = Validator::make($validate, [
                'regra' => ['required', 'int'],
                'perfil' => ['required', 'int'],
                'acesso' => ['required', 'int', 'max:1'],
            ])->validate();
        };

        if ($this->comparaPermissao($request, $id)) {
            return response()->json([
                'message' => 'Não é possível registrar uma permissão customizada exatamente igual a original.',
                'status' => 'ERROR'
            ], 406);
        }

        try {
            DB::beginTransaction();
            foreach ($request->all() as $temp) {
                $this->repository->where('usuario_id', $id)
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
    public function destroy($id)
    {
        $permissoes = $this->repository->where('usuario_id', $id)->get();
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

    protected function comparaPermissao(Request $request, $usuario_id = null)
    {
        $usuario_id = isset($usuario_id) ? $usuario_id : $request[0]['usuario_id'];
        $usuario = Usuarios::find($usuario_id);

        $permissaoOri = Permissao::where('perfil', $usuario->perfil)
            ->get(['regra', 'acesso'])->toArray();

        $permissaoOriginal = array_map(function ($reg) {
            return $reg;
        }, $permissaoOri);

        $permissaoTemp = array_map(function ($reg) {
            return array(
                "regra" => $reg["regra"],
                "acesso" => $reg["acesso"],
            );
        }, $request->all());

        return $permissaoOriginal == $permissaoTemp;
    }

    protected function verificaDuplicidadePermissoes(Request $request, $usuario_id = null)
    {
        $usuario = isset($usuario_id) ? $usuario_id : $request[0]['usuario_id'];
        $permissaoOri = PermissaoCustom::where('usuario_id', $usuario)
            ->get(['regra', 'acesso'])->toArray();

        $permissaoOriginal = array_map(function ($reg) {
            return $reg;
        }, $permissaoOri);

        $permissaoTemp = array_map(function ($reg) {
            return array(
                "regra" => $reg["regra"],
                "acesso" => $reg["acesso"],
            );
        }, $request->all());

        return $permissaoOriginal == $permissaoTemp;
    }

    public function list(Request $request)
    {
        $usuario = Usuarios::with('usuario')->with('perfil')->where('id_usuario', $request->usuario)->get();      
       

        return response()->json([
            'message' => 'Lista de Usuários para mudança de permissão processada com sucesso!',
            'satus' => 'OK',
            'data' => $usuario
        ], 200);

    }
}
