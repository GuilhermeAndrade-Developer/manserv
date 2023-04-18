<?php

namespace Tests\Feature\API;

use App\Models\Usuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use JWTAuth;

class RequisicaoVeiculosTest extends TestCase
{
    protected $usuario;
    protected $token;
    protected $header;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->usuario = Usuarios::where('cpf', '04754826973')->first();
        $this->token = JWTAuth::fromUser($this->usuario);
        $this->header = [
            'Authorization' => "bearer {$this->token}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @dataProvider rotasProvider
     */
    public function testRotasRequisicaoVeiculos($method, $url, $status)
    {
        $response = $this->json($method, $url, [], $this->header);

        $response->assertStatus($status);
    }

    public function rotasProvider()
    {
        return [
            'listaRequisicaoVeiculos' => [
                'method' => 'GET',
                'url' => "api/requisicao-veiculos",
                'status' => 200
            ]
        ];
    }
}