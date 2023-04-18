<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Cidades;
use App\Models\Estados;
use Illuminate\Http\Request;

class UnidadeFederativaController extends Controller
{
    public function listaEstados()
    {
        $estados = Estados::all();

        return response()->json([
            'status' => 'OK',
            'estados' => $estados
        ], 200);
    }

    public function cidades($id_estado)
    {
        $cidades = Cidades::where('estado', $id_estado)->get();

        if (!isset($id_estado) || is_null($id_estado) || $id_estado == '') {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Indique o código do Estado desejado!'
            ], 500);
        }

        return response()->json([
            'status' => 'OK',
            'cidades' => $cidades
        ], 200);
    }

    public function cidade($id_cidade)
    {
        $cidade = Cidades::find($id_cidade);

        if (!isset($id_cidade) || is_null($id_cidade) || $id_cidade == '') {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Indique o código da Cidade desejada!'
            ], 500);
        }

        return response()->json([
            'status' => 'OK',
            'cidade' => $cidade
        ], 200);
    }
}
