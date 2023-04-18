<?php

namespace Tests\Feature\Admin\ACL;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    /**
     * @dataProvider acessosSemAutencicacaoProvider
     */
    public function testAcessosSemAutenticacao($method, $url)
    {
        $this->json($method, $url)->assertUnauthorized();
    }

    public function acessosSemAutencicacaoProvider()
    {
        return [
            "listarPerfilSemAutenticação" => [
                "method" => 'get',
                "url" => "api/perfis",
            ],
            "regrasDisponiveisParaAssociaçaoAPerfilSemAutenticacao" => [
                "method" => 'get',
                "url" => "api/perfil/3/regra/disponivel",
            ]
        ];
    }
}
