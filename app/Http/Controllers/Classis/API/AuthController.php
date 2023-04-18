<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\HistoricoSenha;
use App\Models\Permissao;
use App\Models\PermissaoCustom;
use App\Models\Usuarios;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Message;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class AuthController extends Controller
{
    private $client;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('MSVWS_BASE_URI')
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function login(Request $request) {

        // TODO-Avelino: Incluir pacote de autenticaçao - JWT.
        // FIXME-Avelino: CPF com AD: 66541522715
        // FIXME-Avelino: CPF com AD: 14395708846
        // FIXME-Avelino: CPF sem AD: 02452104825
        // FIXME-Avelino: CPF sem AD: 99080192520
        // FIXME-Avelino: CPF com AD: 04754826973 - Gestor de UT
        // FIXME-Avelino> CPF com AD: 37186590500 - Coordenador aprovador
        // FIXME-Avelino> CPF com AD: 14441830874 - Diretor aprovador
        // FIXME-Avelino> CPF com AD: 00371559030 - Diretor aprovador
        // CNH 04155449417

        // TODO-Avelino: Adicionar validação de device de origem.

        $credentials = $request->only(['usuario', 'senha']);
        $usuario = Usuarios::with('profile')
            ->where('cpf', $credentials['usuario'])
            ->first(['id', 'cpf', 'nome', 'data_expiracao', 'email', 'coligada', 'status', 'senha_atual',
                'id_ut_cc', 'ut_cc', 'perfil', 'possui_ad']);
        $token = null;
        if (!isset($usuario)) {
            return response()->json([
                'message' => 'Usuário inexistente! Entre em contato com o Administrador!',
                'status' => 'ERROR'
            ], 406);
        }

        if (isset($usuario) && $usuario->possui_ad == 1 && $usuario->status == 'A') {
            $credentials['usuario'] = base64_encode($credentials['usuario']);
            $credentials['senha'] = base64_encode($credentials['senha']);

            try {
                $response = $this->client->request('POST', 'dados_adlogin.php', [
                    'form_params' => [
                        'chave' => env('MSVWS_KEY_Login'),
                        'usuario' => $credentials['usuario'],
                        'senha' => $credentials['senha']
                    ]
                ]);

                /** Converte resposta do servidor em array */
                $manserv_login = json_decode($response->getBody()->getContents(), true);

                if (empty($manserv_login)) {
                    return response()->json([
                        'message' => 'Não foi possível validar o acesso com a Manserv.',
                        'status' => 'ERROR'
                    ], 401);
                }

            } catch (ClientException $e) {
                return response()->json([
                    'http-response' => Message::toString($e->getResponse()),
                    'message' => 'Erro durante o acesso ao servidor da Manserv.',
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'status' => 'ERROR'
                ], 404);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Ooops! Problemas durante a execução!',
                    'code' => $e->getCode(),
                    'error' => $e->getMessage(),
                    'status' => 'ERROR'
                ], 400);
            }

            if ($manserv_login['status'] == 1) {
                $token = JWTAuth::fromUser($usuario);
            } else {
                return response()->json([
                    'message' => 'Não foi possível efetuar o login! Verifique se suas credenciais estão corretas!',
                    'status' => 'ERROR'
                ], 406);
            }
        }

        $credentials = [];
        $credentials['cpf'] = $request->usuario;
        $credentials['senha_atual'] = $request->senha;
        

        if (isset($usuario) && $usuario->possui_ad == 0 && $usuario->status == 'A') {

            if ($this->verifica_expiracao_senha($usuario)) {
                $this->resetar_senha($usuario->id);
            }
            if (Hash::check('Alterar Senha', $usuario->senha_atual) || is_null($usuario->senha_atual)) {
                return response()->json([
                    'message' => 'Redefina sua senha de acesso!',
                    'uri' => 'redefinir-senha/',
                    'status' => 'OK'
                ], 301);
            }

            if (! Hash::check($credentials['senha_atual'], $usuario->senha_atual)) {
                return response()->json([
                    'message' => 'Não foi possível efetuar o login! Verifique se suas credenciais estão corretas!',
                    'status' => 'ERROR'
                ], 406);
            }
        }

        if (isset($usuario) && $usuario->status != 'A') {
            return response()->json([
                'message' => 'Acesso temporariamente suspenso!',
                'status' => 'ERROR'
            ], 401);
        }

        if (isset($Usuario) && $usuario->possui_ad == 1) {
            $token = JWTAuth::fromUser($usuario);
        } else if (Hash::check($credentials['senha_atual'], $usuario->senha_atual)) {
            $token = JWTAuth::fromUser($usuario);
        }

        return response()->json([
            'message' => 'Login efetuado com sucesso!',
            'tipo_token' => 'bearer',
            'token' => $token,
            'data' => $usuario->me(),
            'status' => 'OK'
        ], 200);
    }
    
    public function resetar_senha(int $id) {
        try {
            DB::beginTransaction();
            $usuario = Usuarios::find($id);
            $historico_senha = new HistoricoSenha();

            $historico_senha->senha = $usuario->senha_atual;
            $historico_senha->data_expiracao = !$usuario->data_expiracao ? date('Y-m-d', strtotime(Carbon::now())) : $usuario->data_expiracao;
            $historico_senha->id_usuario = $usuario->id;
            $historico_senha->save();

            $usuario->senha_atual = Hash::make('Alterar Senha');
            $usuario->save();

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ooops! Não foi possível efetuar o reset de sua senha!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Senha resetada com sucesso!',
            'status' => 'OK'
        ], 201);
    }

    public function redefinir_senha(AuthRequest $request) {
        
        $credentials = $request->only(['usuario', 'senha', 'confirma_senha']);
        $usuario = Usuarios::where('cpf', $credentials['usuario'])->first();

        if (isset($usuario) && $usuario->possui_ad == 0 && $usuario->status != 'A') {
            return response()->json([
                'message' => 'Acesso temporariamente suspenso!',
                'status' => 'ERROR'
            ], 401);
        }

        if (!isset($usuario)) {
            return response()->json([
                'message' => 'Usuário não localizado!',
                'status' => 'ERROR'
            ], 404);
        }

        $historico_senha = HistoricoSenha::where('id_usuario', $usuario->id)->orderby('id', 'DESC')->first();

        if (isset($historico_senha) && Hash::check($credentials['senha'], $historico_senha->senha)) {
            return response()->json([
                'message' => 'Você não pode utilizar a senha anterior!',
                'status' => 'ERROR'
            ], 406);
        }

        $credentials['senha'] = Hash::make($credentials['senha']);

        if (!Hash::check($credentials['confirma_senha'], $credentials['senha'])) {
            return response()->json([
                'message' => 'Senhas incompatíveis!',
                'status' => 'ERROR'
            ], 406);
        }

        if (isset($usuario) && $usuario->possui_ad == 0) {
            try {
                DB::beginTransaction();
                $usuario->senha_atual = $credentials['senha'];
                $usuario->data_expiracao = date('Y-m-d', strtotime(Carbon::now()->addDays(90)));

                $historico = new HistoricoSenha();
                $historico->data_expiracao = $usuario->data_expiracao;
                $historico->senha = $credentials['senha'];
                $historico->id_usuario = $usuario->id;

                $historico->save();

                $usuario->save();
                DB::commit();
            } catch (QueryException $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Ooops! Não foi possível efetuar o cadastro de sua senha!',
                    'code' => $e->getCode(),
                    'status' => 'ERROR'
                ], 404);
            }
        }

        return response()->json([
            'message' => 'Senha alterada com sucesso!',
            'status' => 'OK'
        ], 201);
    }

    public function verifica_expiracao_senha(Usuarios $usuario) {
        $hoje = date('Y-m-d', strtotime(Carbon::now()));

        if (strtotime($usuario->data_expiracao) == strtotime($hoje)) {
            return true;
        }

        return false;
    }
        
    public function permissao(Usuarios $usuario) {
        /**
         * TODO-Avelino: Validar esta funçao de permissao de usuarios, escrita por Guilherme, provavelmente esta apresentando e erros e nao retorna nenhuma permissao.
         */

        $perfil = $usuario->perfil;

        if ($perfil == 3 || $perfil == 4 || $perfil == 5) {
                $this->middleware('auth')->only(['listacondutor', 'registrarcondutor', 'mostrarcondutor', 'atualizarcondutor']);
        }

        if ($perfil == 8) {
            $this->middleware('auth')->only(['listacondutor', 'condutor']);
            $this->middleware('auth')->except(['registrarcondutor', 'atualizarcondutor']);
        
        }else if ($perfil == 7 || $perfil == 8) {
            $this->middleware('auth')->only(['login']);
        }
    }

}
