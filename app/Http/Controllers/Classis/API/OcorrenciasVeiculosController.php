<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Ocorrencias;
use App\Models\OcorrenciasVeiculos;
use App\Models\Condutores;
use App\Models\Checklists;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class OcorrenciasVeiculosController extends Controller
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
            $tempOcorrencias = OcorrenciasVeiculos::with('veiculo')->with('ocorrencia')->get();
             
            $ocorrencias = null;
            
            foreach ($tempOcorrencias as $oco) {
                $ocorrencias[] = [
                    'placa' => $oco->veiculo->placa,
                    'tipo_ocorrencia' => $oco->tipo_ocorrencia,
                    'data_cadastro' => $oco->ocorrencia->cadastro,
                    'foto_link' => $oco->ocorrencia->foto_link,
                    'descricao' =>$oco->ocorrencia->descricao,
                ];                
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar Ocorrencias', 
                'code' => $e->getCode(). $e->getMessage(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de ocorrencias!',
            'status' => 'OK',
            'data' => $ocorrencias
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @ param int $id
     */
    public function exibir($idVeiculo, $idCondutor)
    {
        try {
            $checklist = Checklists::with('condutor.usuario')->with('veiculo.ocorrencias.ocorrencia')->where('id_veiculo',$idVeiculo)->where('id_condutor',$idCondutor)->get();
               
            
            foreach ($checklist as $oco) {                
                $items=null;
                foreach($oco->veiculo->ocorrencias as $item)
                {
                    $items[] = [
                        "id"=> $item->id,
                        "tipo_ocorrencia"=> $item->tipo_ocorrencia,
                        "data_cadastro" => $item->ocorrencia->data_cadastro,
                        "foto_link" => $item->ocorrencia->foto_link,
                        "descricao" => $item->ocorrencia->descricao,
                    ]; 
                }
                $ocorrencias[] = [
                    'Nome Condutor' => $oco->condutor->usuario->nome,
                    'UT condutor' => '9.'.$oco->condutor->usuario->ut_cc,
                    'Placa Veiculo' => $oco->veiculo->placa,
                    'ocorrencias' => $items,
                ];                
            }                      
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar Ocorrencias', 
                'code' => $e->getCode(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de ocorrencias por Veiculo e Condutor!',
            'status' => 'OK',
            'data' => $ocorrencias
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
       try
       {
        $foto = $request->file('foto');
        $destino = 'upload';
        $fotoName = str_replace('-','',$request->cadastro).'_'.$request->id_veiculo.'_'.$request->tipo_ocorrencia.'.'.$foto->getClientOriginalExtension();
        $foto_link = url('storage/upload/'.$fotoName);
        
        $foto->move($destino,$fotoName);
        
        $ocorrencia = [
            'id_veiculo'=>$request->id_veiculo,
            'tipo_ocorrencia'=>$request->tipo_ocorrencia,
        ];

        $ocorrencia = OcorrenciasVeiculos::create($ocorrencia);

        $complemento = [
            'id_ocorrenciaVeiculos'=> $ocorrencia->id,
            'data_cadastro'=>$request->data_cadastro,
            'foto_link'=> $foto_link,
            'descricao'=>$request->descricao,
        ];

        $complemento = Ocorrencias::create($complemento);
     }catch (\Exception $e) {
        return response()->json([
            'mensagem' => 'Não foi possivel cadastrar Ocorrencia', 
            'code' => $e->getCode().$e->getMessage(),
            'status' => 'ERROR',
        ], 400);
    }

    return response()->json([
        'message' => 'Ocorrencia gravada com sucesso!',
        'status' => 'OK',      
    ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $tempOcorrencias = OcorrenciasVeiculos::with('veiculo')->with('ocorrencia')->where('id',$id)->get();  
            $ocorrencias = null;
            
            foreach ($tempOcorrencias as $oco) {
                
                $ocorrencias[] = [
                    'placa' => $oco->veiculo->placa,
                    'tipo_ocorrencia' => $oco->tipo_ocorrencia,
                    'data_cadastro' => $oco->ocorrencia->data_cadastro,
                    'foto_link' => $oco->ocorrencia->foto_link,
                    'descricao' =>$oco->ocorrencia->descricao,
                ];                
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar Ocorrencias', 
                'code' => $e->getCode(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de ocorrencias!',
            'status' => 'OK',
            'data' => $ocorrencias
        ], 200);
    }
    
}
