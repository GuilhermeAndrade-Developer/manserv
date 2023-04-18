<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\ValorCombustivel;
use Illuminate\Http\Request;

class CartaoCombustivelController extends Controller
{
    public function calculaLimiteCartao(Request $request)
    {
        // FIXME-Avelino: Parâmetros para cálculo do combustível: cat=1&capac=1&km=5000&local=2&uf=26
        $query = $request->query();

        if (empty($query)) {
            return response()->json([
                'message' => 'Não foi possível realizar o cálculo!',
                'status' => 'ERROR'
            ], 406);
        }

        $valor = ValorCombustivel::
            where('categoria', $query['cat'])
            ->where('capacidade', $query['capac'])
            ->where('km', $query['km'])
            ->where('local', $query['local'])
//            ->where('estado', $query['uf'])
            ->first();

        $limiteCartao = ($valor->km / $valor->autonomia) * $valor->valor;

        return response()->json([
            'message' => 'Cálculo efetuado!',
            'status' => 'OK',
            'data' => $limiteCartao
        ], 200);
    }
}
