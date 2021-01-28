<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Condutores;
use App\Models\Pendencia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class TermosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @ param int $id
     */
    public function index(int $id)
    {
        try {
            $tmpPendencias = Pendencia::with('condutor')->get();
            $condutor = Condutores::with('usuario')->where('id_usuario', $id)->first();
            $pendencias = null;

            foreach ($tmpPendencias as $pendencia) {
                if ($pendencia->condutor->id_usuario == $id) {
                    $pendencias[] = [
                        'id' => $pendencia->id,
                        'id_condutor' => $condutor->id_usuario,
                        'nome_condutor' => $condutor->usuario->nome,
                        'id_veiculo' => $pendencia->id_veiculo,
                        'data_chklist' => $pendencia->data_chklist,
                        'tipo_chklist' => $pendencia->tipo_chklist,
                        'atendido' => $pendencia->atendido,
                        'aceite' => $pendencia->aceite,
                    ];
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel executar a consulta de Pendências!',
                'code' => $e->getCode(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de pendências!',
            'status' => 'OK',
            'data' => $pendencias
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
       //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    
}
