<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Locadoras;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LocadorasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $locadoras = Locadoras::all();

        return response()->json([
            'message' => 'Listagem processada com sucesso!',
            'status' => 'OK',
            'data' => $locadoras
        ], 200);
        
    }
}