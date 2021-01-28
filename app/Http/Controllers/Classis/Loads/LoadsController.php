<?php

namespace App\Http\Controllers\Classis\Loads;

use App\Http\Controllers\Controller;
use App\Models\GestoresUt;
use App\Models\Usuarios;
use App\Models\UT;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LoadsController extends Controller
{
    private $client;

    /**
     * LoadsController constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('MSVWS_BASE_URI')
        ]);
    }

    /**
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function usuarios() {
        try {
            $response = $this->client->request('GET', 'dados_rhfuncs.php?chave=' . env('MSVWS_KEY_Users'));
        } catch (ClientException $e) {
            return response()->json([
                'http-response' => Message::toString($e->getResponse()),
                'code' => $e->getCode()
            ], 400);
        }

        /** Altera o campo de sincronismo para 0 para validar a nova carga dos usuarios */
        Usuarios::where('sync', 1)->update(['sync' => 0]);

        $manserv_users = json_decode($response->getBody(), true);

        /** Se o servidor nao conseguir retornar a requisiçao, devolver a mensagem enviada pelo servidor da Manserv */
        if (!isset($manserv_users['resultado'])) {
            return response()->json([
                'message' => 'Nao foi possivel acessar o servidor!',
                'error' => $manserv_users['messagem']
            ], 500);
        }

        $adicionados = 0;
        $atualizados = 0;
        $excluidos = 0;

        foreach ($manserv_users['resultado']['list'] as $usuario) {
            try {
                $bd_usuario = Usuarios::where('cpf', $usuario['CPF'])->first();

                /** Atualiza usuario */
                if (!empty($bd_usuario)) {

                    $ut = UT::where('numero_ut', '9.' . $usuario['CODSECAO'])->first();

                    $bd_usuario->nome = trim($usuario["NOME"]);
                    $bd_usuario->coligada = trim($usuario['CODCOLIGADA']);
                    $bd_usuario->email = trim($usuario['EMAIL']);
                    $bd_usuario->status = trim($usuario['STATUS']);
                    $bd_usuario->id_ut_cc = !empty($ut->id) ? $ut->id : 99999;
                    $bd_usuario->ut_cc = trim($usuario['CODSECAO']);
                    $bd_usuario->possui_ad = trim($usuario['AD']);
                    $bd_usuario->sync = 1;

                    $bd_usuario->save();
                    $atualizados++;
                }

                /** Adiciona usuario */
                if (empty($bd_usuario)) {
                    $bd_usuario = new Usuarios();

                    $ut = UT::where('numero_ut', '9.' . $usuario['CODSECAO'])->first();

                    $bd_usuario->cpf = trim($usuario['CPF']);
                    $bd_usuario->nome = trim($usuario["NOME"]);
                    $bd_usuario->coligada = trim($usuario['CODCOLIGADA']);
                    $bd_usuario->email = trim($usuario['EMAIL']);
                    $bd_usuario->status = trim($usuario['STATUS']);
                    $bd_usuario->id_ut_cc = !empty($ut->id) ? $ut->id : 99999;
                    $bd_usuario->ut_cc = trim($usuario['CODSECAO']);
                    $bd_usuario->possui_ad = trim($usuario['AD']);
                    $bd_usuario->perfil = 8;
                    $bd_usuario->sync = 1;

                    $bd_usuario->save();
                    $adicionados++;
                }
                unset($bd_usuario);
            } catch (\Exception $e) {
                return response()->json([
                    'code' => $e->getCode(),
                    'status' => 'ERROR'
                ], 400);
            }
        }

        /** Inativa usuario */
        $excluidos = Usuarios::where('sync', 0)->update(['status' => 'I', 'sync' => 1]);

        $registros = [
            'adicionados' => $adicionados,
            'atualizados' => $atualizados,
            'excluidos' => $excluidos
        ];

        return response()->json([
            'message' => 'Usuarios cadastrados com sucesso!',
            'registros' => $registros,
            'status' => 'OK'
        ], 201);
    }

    /**
     * @return JsonResponse
     */
    public function gestores_ut() {

        try {
            $response = $this->client->request('GET', 'dados_hierarquia.php?chave=' . env('MSVWS_KEY_UT_CC'));
        } catch (RequestException $e) {
            return response()->json([
                'request' => Message::toString($e->getRequest()),
                'response' => $e->getResponse(),
                'code' => $e->getCode()
            ], 400);
        }

        $manserv_gestores = json_decode($response->getBody(), true);

        /** Se o servidor nao conseguir retornar a requisiçao, devolver a mensagem enviada pelo servidor da Manserv */
        if (!isset($manserv_gestores['resultado'])) {
            return response()->json([
                'message' => 'Nao foi possivel acessar o servidor Manserv!',
                'error' => $manserv_gestores['messagem']
            ], 500);
        }

        foreach ($manserv_gestores['resultado']['list'] as $gestor) {

            /** Verificaçao Presidente */
            if (!empty($gestor['diretor_presidente'])) {
                try {
                    $usuario = Usuarios::where('cpf', $gestor['diretor_presidente_cpf'])->first();
                    $ut_cc = UT::where('numero_ut', trim($gestor['codut']))->first();

                    $tbGestor = new GestoresUt();
                    if (isset($usuario) && isset($ut_cc)) {
                        $tbGestor->data_inicio = Carbon::now();
                        $tbGestor->tipo_gestor = 'P';
                        $tbGestor->id_gestor = $usuario->id;
                        $tbGestor->id_ut_cc = $ut_cc->id;

                        $tbGestor->save();

                        $usuario->perfil = 5;
                        $usuario->save();
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'code' => $e->getCode()
                    ], 400);
                } finally {
                    unset($tbGestor);
                }
            }

            /** Verificaçao Vice Presidente */
            if (!empty($gestor['diretor_vpresidente'])) {

                try {
                    $dbVpresidente = new GestoresUt();
                    $usuario = Usuarios::where('cpf', $gestor['diretor_vpresidente_cpf'])->first();
                    $ut_cc = UT::where('numero_ut', trim($gestor['codut']))->first();

                    if (isset($usuario) && isset($ut_cc)) {
                        $dbVpresidente->data_inicio = Carbon::now();
                        $dbVpresidente->tipo_gestor = 'V';
                        $dbVpresidente->id_gestor = $usuario->id;
                        $dbVpresidente->id_ut_cc = $ut_cc->id;

                        $dbVpresidente->save();

                        $usuario->perfil = 5;
                        $usuario->save();
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'code' => $e->getCode()
                    ], 400);
                } finally {
                    unset($dbVpresidente);
                }
            }

            /** Verificaçao Diretor */
            if (!empty($gestor['diretor'])) {

                try {
                    $dbDiretor = new GestoresUt();
                    $usuario = Usuarios::where('cpf', $gestor['diretor_cpf'])->first();
                    $ut_cc = UT::where('numero_ut', trim($gestor['codut']))->first();

                    if (isset($usuario) && isset($ut_cc)) {
                        $dbDiretor->data_inicio = Carbon::now();
                        $dbDiretor->tipo_gestor = 'D';
                        $dbDiretor->id_gestor = $usuario->id;
                        $dbDiretor->id_ut_cc = $ut_cc->id;

                        $dbDiretor->save();

                        $usuario->perfil = 5;
                        $usuario->save();
                    }
                }  catch (\Exception $e) {
                    return response()->json([
                        'code' => $e->getCode()
                    ], 400);
                } finally {
                    unset($dbDiretor);
                }
            }

            /** Verificaçao Gerente */
            if (!empty($gestor['gerente'])) {
                try {
                    $dbGerente = new GestoresUt();
                    $usuario = Usuarios::where('cpf', $gestor['gerente_cpf'])->first();
                    $ut_cc = UT::where('numero_ut', trim($gestor['codut']))->first();

                    if (isset($usuario) && isset($ut_cc)) {
                        $dbGerente->data_inicio = Carbon::now();
                        $dbGerente->tipo_gestor = 'G';
                        $dbGerente->id_gestor = $usuario->id;
                        $dbGerente->id_ut_cc = $ut_cc->id;

                        $dbGerente->save();

                        $usuario->perfil = 5;
                        $usuario->save();
                    }
                }  catch (\Exception $e) {
                    return response()->json([
                        'code' => $e->getCode()
                    ], 400);
                } finally {
                    unset($dbGerente);
                }
            }

            /** Verificaçao Responsavel */
            if (!empty($gestor['responsavel'])) {

                try {
                    $dbResponsavel = new GestoresUt();
                    $usuario = Usuarios::where('cpf', $gestor['responsavel_cpf'])->first();
                    $ut_cc = UT::where('numero_ut', trim($gestor['codut']))->first();

                    if (isset($usuario) && isset($ut_cc)) {
                        $dbResponsavel->data_inicio = Carbon::now();
                        $dbResponsavel->tipo_gestor = 'R';
                        $dbResponsavel->id_gestor = $usuario->id;
                        $dbResponsavel->id_ut_cc = $ut_cc->id;

                        $dbResponsavel->save();

                        $usuario->perfil = 5;
                        $usuario->save();
                    }
                }  catch (\Exception $e) {
                    return response()->json([
                        'code' => $e->getCode()
                    ], 400);
                } finally {
                    unset($dbResponsavel);
                }
            }

            /** Verificaçao Admin */
            if (!empty($gestor['admin'])) {

                try {
                    $dbAdministrador = new GestoresUt();
                    $usuario = Usuarios::where('cpf', $gestor['admin_cpf'])->first();
                    $ut_cc = UT::where('numero_ut', trim($gestor['codut']))->first();

                    if (isset($usuario) && isset($ut_cc)) {
                        $dbAdministrador->data_inicio = Carbon::now();
                        $dbAdministrador->tipo_gestor = 'A';
                        $dbAdministrador->id_gestor = $usuario->id;
                        $dbAdministrador->id_ut_cc = $ut_cc->id;

                        $dbAdministrador->save();

                        $usuario->perfil = 3;
                        $usuario->save();
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'code' => $e->getCode()
                    ], 400);
                } finally {
                    unset($dbAdministrador);
                }
            }
        }

        return response()->json([
            'message' => 'Gestores cadastrados com sucesso!',
        ], 201);
    }


    /**
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function ut_cc() {
        try {
            $response = $this->client->request('GET', 'dados_hierarquia.php?chave=' . env('MSVWS_KEY_UT_CC'));
        } catch (RequestException $e) {
            return response()->json([
                'request' => Message::toString($e->getRequest()),
                'response' => $e->getResponse(),
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 400);
        }

        $manserv_hierarquia = json_decode($response->getBody(), true);

        $insert_dados = [];
        try {
            foreach ($manserv_hierarquia['resultado']['list'] as $ut_cc) {
                $insert_dados[] = [
                    'numero_ut' => trim($ut_cc['codut']),
                    'descricao' => trim($ut_cc['descricao']),
                    'numero_coligada' => trim($ut_cc['coligada']),
                    'coligada_cnpj' => str_replace('/', '', str_replace('-', '', str_replace('.', '', trim($ut_cc['coligada_cnpj'])))),
                    'coligada_fantasia' => trim($ut_cc['coligada_fantasia']),
                    'cidade' => trim($ut_cc['cidade']),
                    'status' => trim($ut_cc['statusut']) == 'ATIVA' ? 'A' : 'I',
                    'ano_mes_inicio' => trim($ut_cc['anomesini']),
                    'ano_mes_fim' => trim($ut_cc['anomesfim']),
                    'negocio_bu' => trim($ut_cc['negocio_bu']),
                    'regional' => trim($ut_cc['regional']),
                    'tipo_despesa' => trim($ut_cc['tipo_despesa']),
                    'regiao' => trim($ut_cc['regiao']),
                    'segmento' => trim($ut_cc['Segmento']),
                    'grupo_cliente' => trim($ut_cc['grupo_cliente']),
                    'diretor_presidente' => trim($ut_cc['diretor_presidente']),
                    'diretor_presidente_cpf' => trim($ut_cc['diretor_presidente_cpf']),
                    'diretor_vpresidente' => trim($ut_cc['diretor_vpresidente']),
                    'diretor_vpresidente_cpf' => trim($ut_cc['diretor_vpresidente_cpf']),
                    'diretor' => trim($ut_cc['diretor']),
                    'diretor_cpf' => trim($ut_cc['diretor_cpf']),
                    'gerente' => trim($ut_cc['gerente']),
                    'gerente_cpf' => trim($ut_cc['gerente_cpf']),
                    'responsavel' => trim($ut_cc['responsavel']),
                    'responsavel_cpf' => trim($ut_cc['responsavel_cpf']),
                    'admin' => trim($ut_cc['admin']),
                    'admin_cpf' => trim($ut_cc['admin_cpf'])
                ];

            }
        } catch (\Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'error' => 'Ocorreu um erro ao cadastrar as UTs.',
                'status' => 'ERROR'
            ], 400);
        }

        try {
            DB::table('UT_CC')->insert($insert_dados);
        } catch (QueryException $e) {
            return response()->json([
                'code' => $e->getCode(),
                'error' => $e->errorInfo,
                'msg' => $e->getMessage(),
                'status' => 'ERROR'
            ], 400);
        }

        return response()->json([
            'message' => 'UTs cadastradas com sucesso!',
            'status' => 'OK'
        ], 201);
    }
}
