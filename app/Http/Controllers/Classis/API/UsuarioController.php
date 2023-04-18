<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use App\Models\Condutores;
use App\Models\GestoresUt;
use App\Models\UT;
use App\Models\UsuariosRepresentantes;
use App\Exceptions\CpfException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function lista()
    {
        $usuario = Usuarios::all();
        return response()->json([
            'message' => 'Listagem de usuarios',
            'status' => 'OK',
            'data' => $usuario
        ], 200);
    }

        /**
     * Display the specified resource.
    
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function exibir(Request $request)
    {
        try {
//            $logado
            $usuario = Usuarios::where('cpf', $request->cpf)->where('status', 'A')->first();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar o Usuário.',
                'code' => $e->getCode(),
                'status' => 'Error'
            ], 404);
        }

        if (!isset($usuario)) {
            return response()->json([
                'message' => 'Usuário não encontrado, verifique se o CPF está correto!',
                'status' => 'ERROR',
                'data' => $usuario
            ], 406);
        }

        return response()->json([
            'message' => 'Usuário encontrado!',
            'status' => 'OK',
            'data' => $usuario
        ], 200);
    }

    /**
     * @param $id
     * @param  Request $request
     * @return JsonResponse
     */
    public function perfil(Request $request, $id)
    {
        $usuario = Usuarios::where('id', $id)->first(['id', 'cpf', 'nome', 'data_expiracao', 'email', 'coligada', 'status',
            'id_ut_cc', 'ut_cc', 'perfil', 'possui_ad']);      

        $perfil = $usuario->me();
        $condutor = Condutores::where('id_usuario',$id);
        if($condutor->count()>0){
            $perfil['condutor'] = $condutor->get();
        }
        return response()->json([
            "message" => "Perfil completo do usuário!",
            "data" => $perfil,
            "status" => "OK"
        ], 200);
    }

    public function filtrar($id)
    {
        try {
            
            $usuario = Usuarios::with('condutor')->find($id);
            $utId[] = $usuario->id_ut_cc;
            $gestor = GestoresUt::where('id_gestor',$id)->get();
            
            foreach($gestor as $k => $v)
            {
                if(!in_array($v->id_ut_cc,$utId)){
                    $utId[] = $v->id_ut_cc;
                }                
            }
            
            $uts = UT::whereIn('id',$utId)->get();
           
            $cont =0;
            $retornoUts = null;
            $retornoUs = null;
            foreach($uts as $k => $v)
            {
                $retornoUts[] = ['id'=>$v->id,'numero_ut'=>$v->numero_ut,'descricao'=>$v->descricao];
                $usuarios = Usuarios::where('id_ut_cc',$v->id)->get();
                foreach($usuarios as $j => $m){
                    $retornoUs[] = ['numero_ut'=>$v->numero_ut,'id'=>$m->id,'cpf'=>$m->cpf,'nome'=>$m->nome];
                }
            }
          $retorno = ['uts'=>$retornoUts,'usuarios'=> $retornoUs];
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar o Usuário.',
                'code' => $e->getCode(),
                'status' => 'Error'
            ], 404);
        }    
        return response()->json([
                                    "message" => "Usuarios encontrados por filtro",
                                    "data" => $retorno,
                                    "status" => "OK"
                                ], 200);
    }

    public function representa(int $id)
    {
        $usuario = (new Usuarios())->find($id);

        if ($usuario->isRepresentative($id)) {
            $uts = null;
            $representante = $usuario->hasRepresentative($id);
            foreach($representante as $ut)
            {      
                if($uts==null){
                    $uts[] = ['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut']];
                  }
                if(!in_array(['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut']],$uts)){
                    $uts[] = ['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut']];                        
                }
            }
            return response()->json([
                "message" => "UTs Representadas!",
                "status" => "OK",
                "uts" => $uts
            ], 200);
        }

        return response()->json([
            "message" => "Nenhuma UT encontrada para este usuário!",
            "status" => "OK"
        ], 200);
    }

    public function gerencia(int $id)
    {
        $usuario = (new Usuarios())->find($id);

        if ($usuario->isManager($id)) {
            $uts = null;
            $manager = $usuario->hasManager($id);
            foreach($manager as $ut)
            {                   
                if($uts==null){
                    $uts[] = ['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut'], 'descricao' =>  $ut['descricao']];
                  }
                if(!in_array(['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut'], 'descricao' =>  $ut['descricao']],$uts)){
                    $uts[] = ['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut'], 'descricao' =>  $ut['descricao']];                        
                }
            }
            
            return response()->json([
                "message" => "UTs Que Gerencia!",
                "status" => "OK",
                "uts" => $uts
            ], 200);
        }

        return response()->json([
            "message" => "Nenhuma UT encontrada para este usuário!",
            "status" => "OK"
        ], 200);
    }
    public function gerenciaAll(int $id)
    {
        $usuario = (new Usuarios())->find($id);
        $uts = null;
        if ($usuario->isRepresentative($id)) {            
            $representante = $usuario->hasRepresentative($id);
            foreach($representante as $ut)
            {                   
                if($uts==null){
                    $uts[] = ['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut'], 'descricao' =>  $ut['descricao']];
                  }
                if(!in_array(['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut'], 'descricao' =>  $ut['descricao']],$uts)){
                    $uts[] = ['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut'], 'descricao' =>  $ut['descricao']];                        
                }
            }
        }
        if ($usuario->isManager($id)) {
              $manager = $usuario->hasManager($id);
                foreach($manager as $ut)
                {                   
                    if($uts==null){
                        $uts[] = ['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut'], 'descricao' =>  $ut['descricao']];
                      }
                    if(!in_array(['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut'], 'descricao' =>  $ut['descricao']],$uts)){
                        $uts[] = ['id'=>$ut['id'],'numero_ut'=>$ut['numero_ut'], 'descricao' =>  $ut['descricao']];                        
                    }
                }
            }    
            return response()->json([
                "message" => "UTs Que Gerencia!",
                "status" => "OK",
                "uts" => $uts
            ], 200);
    }
}