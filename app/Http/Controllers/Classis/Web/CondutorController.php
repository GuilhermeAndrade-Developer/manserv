<?php

namespace App\Http\Controllers\Classis\Web;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\{
    Request,
    Response
};
use Illuminate\Support\Facades\Http;
use JWTAuth;

class CondutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->authorize("cadastrar_condutor");

        $usuario = auth()->user();
        $token = JWTAuth::fromUser($usuario);

        $perfil = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "bearer {$token}"
        ])->get(env("APP_URL") . "/api/usuarios/me/{$usuario->id}");

        return view('front.condutor', [
            'ut' => $perfil['data']['ut'],
            'usuario' => $perfil['data']['usuario']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize("cadastrar_condutor");

       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->authorize("cadastrar_condutor");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $this->authorize("cadastrar_condutor");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $this->authorize("cadastrar_condutor");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize("cadastrar_condutor");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->authorize("cadastrar_condutor");
    }
}
