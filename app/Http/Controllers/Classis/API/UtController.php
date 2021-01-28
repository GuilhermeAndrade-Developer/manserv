<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\UT;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */

     public function lista() {

        try {
            $manserv_uts = UT::all();

        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar as UTs.',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de UTs.',
            'status' => 'OK',
            'data' => $manserv_uts
        ], 200);
     }

    public function filtrarUt(int $id)
    {
        $ut = UT::find($id);

        if ($ut) {
            return response()->json([
                'message' => 'UT selecionada!',
                'status' => 'OK',
                'ut' => $ut->toArray()
            ]);
        }
    }
}