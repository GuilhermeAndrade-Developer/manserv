<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marcas;

class MarcaController extends Controller
{
    private $mensagem;
    
    public function listar()
    {
        try{
        
            $marcas = Marcas::all();
            if(!$marcas) {
                $marcas = [];
                $this->mensagem = 'Nenhuma Marca Encontrada';
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
            'data' => $marcas,
        ], 200);

    }
}