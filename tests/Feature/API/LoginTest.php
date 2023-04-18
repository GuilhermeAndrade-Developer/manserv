<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * Login Test Without Informations for User.
     *
     * @return void
     */
    public function testLoginSemDadosParaAcesso()
    {
        $response = $this->post('/api/login');

        $response->assertStatus(302);
    }

    /**
     * Login Test.
     *
     * @return void
     */
    public function testLoginComRetornoDoJson()
    {
        $response = $this->postJson('/api/login', [
            'usuario' => '14395708846',
            'senha' => 'teste.homologacao123'
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message', 'tipo_token', 'token', 'data' => [
                'usuario' => [
                    'id', 'cpf', 'nome', 'data_expiracao', 'email', 'coligada', 'status', 'id_ut_cc',
                    'ut_cc', 'perfil', 'possui_ad'
                ],
                'perfil' => [
                    'id', 'nome', 'descricao'
                ],
                'permissoes' => [
                    'data' => [
                        '*' => [
                            'id', 'regra', 'regras' => [
                                'id', 'nome', 'descricao', 'secao'
                            ]
                        ]
                    ]
                ],
                'ut' => [
                    'id', 'numero_ut', 'descricao', 'numero_coligada', 'cidade', 'status', 'ano_mes_inicio', 'ano_mes_fim',
                    'negocio_bu', 'regional', 'tipo_despesa', 'regiao', 'segmento', 'grupo_cliente'
                ],
                'gestores' => [
                    '*' => [
                        'data_inicio',
                        'tipo_gestor',
                        'id_gestor' => [
                            'cpf', 'nome', 'data_expiracao', 'email', 'coligada', 'status', 'id_ut_cc', 'ut_cc',
                            'possui_ad', 'perfil'
                        ],
                        'id_ut_cc', 'data_fim'
                    ]
                ]
            ],
            'status'
        ]);
    }
}
