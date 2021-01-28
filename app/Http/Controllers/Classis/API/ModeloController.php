<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modelos;

class ModeloController extends Controller
{
    private $mensagem = 'Modelos Listados com Sucesso';
    
    public function listarModeloPorMarca($marca) 
    {
        try{
        
            $modelos = Modelos::where('id_marca', $marca)->get();
            if(!$modelos->count()) {
                $modelos = [];
                $this->mensagem = 'Nenhuma Modelo Encontrado';
            }

        }catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'status' => 'ERRO',
                'data' => [],
            ], 500);
        }

        return response()->json([
            'message' => $this->mensagem,
            'status' => 'OK',
            'data' => $modelos,
        ], 200);

    }
    
}