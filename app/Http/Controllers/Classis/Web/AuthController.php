<?php

namespace App\Http\Controllers\Classis\Web;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Stream;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private $client;
    private $headers;

    public function __construct()
    {
        $this->client = new Client(["base_uri" => env("APP_URL")]);
        $this->headers = [
            'headers' => []
        ];
    }

    public function authenticate(Request $request)
    {
        $credentials['cpf'] = $request->usuario;
        $credentials['senha_atual'] = Hash::make($request->senha);

        $response = Http::post(env("APP_URL") . '/api/login', $request->only(['usuario', 'senha']));

        if (!$response->successful()) {
            return response()->json(
                $response->json()
                , $response->status());
        }

        $usuarioLogado = Auth::loginUsingId($response->json()['data']['usuario']['id']);

        if (! Auth::check($usuarioLogado)) {
            return response()->json([
                'message' => 'Não foi possível efetuar a autenticação! Verifique se suas credenciais estão corretas e/ou se você realmente possui cadastro no sistema.',
                'status' => 'ERROR'
            ], 406);
        }

        $message = $response->json()['message'];
        $status = $response->json()['status'];
        $tipo_token = $response->json()['tipo_token'];
        $token = $response->json()['token'];
        $usuario = $response->json()['data']['usuario'];
        $funcao = $response->json()['data']['perfil']['descricao'];
        $ut = $response->json()['data']['ut']['numero_ut'] . " - " . $response->json()['data']['ut']['descricao'];

        return response()->json([
            "message" => $message,
            "status" => $status,
            "tipo_token" => $tipo_token,
            "token" => $token,
            "usuario" => $usuario,
            "funcao" => $funcao,
            "ut" => $ut
        ], $response->status());

    }

    public function logout()
    {
        $usuario = Auth::user();

        if (Auth::check($usuario)) {
            Auth::logout();
        }

        return response()->redirectToRoute('login');
    }
}
