<?php


namespace App\Models\Traits;


use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait UserPermissionsTrait
{
    public function permissions(int $id_usuario)
    {
        Auth::loginUsingId($id_usuario);

        $usuario = Auth::user()
            ->where('id', $id_usuario)
            ->where('status', 'A')
            ->with('profile')
            ->first();

        return $usuario;
    }

    public function isManager(int $id_usuario): bool
    {
        if (isset($this->permissions($id_usuario)->manager)
            && $this->permissions($id_usuario)->manager->count() > 0) {
            return true;
        }
        return false;
    }

    public function hasManager(int $id_usuario)
    {
        $uts = [];
        array_push($uts, $this->ut->toArray());

        if (!empty($this->permissions($id_usuario)->manager)) {
            foreach ($this->permissions($id_usuario)->manager as $ut) {
                $temp['id'] = $ut->id;
                $temp['numero_ut'] = $ut->numero_ut;
                $temp['descricao'] = $ut->descricao;
                $temp['numero_coligada'] = $ut->numero_coligada;
                $temp['coligada_fantasia'] = $ut->coligada_fantasia;
                $temp['coligada_cnpj'] = $ut->coligada_cnpj;
                $temp['cidade'] = $ut->cidade;
                $temp['status'] = $ut->status;
                $temp['ano_mes_inicio'] = $ut->ano_mes_inicio;
                $temp['ano_mes_fim'] = $ut->ano_mes_fim;
                $temp['negocio_bu'] = $ut->negocio_bu;
                $temp['regional'] = $ut->regional;
                $temp['tipo_despesa'] = $ut->tipo_despesa;
                $temp['regiao'] = $ut->regiao;
                $temp['segmento'] = $ut->segmento;
                $temp['grupo_cliente'] = $ut->grupo_cliente;

                array_push($uts, $temp);
            }
        }

        return $uts;
    }

    public function isRepresentative(int $id_usuario): bool
    {
        if (isset($this->permissions($id_usuario)->representative)
            && $this->permissions($id_usuario)->representative->count() > 0) {
            return true;
        }

        return false;
    }

    public function hasRepresentative(int $id_usuario)
    {
        $uts = [];
        array_push($uts, $this->ut->toArray());

        if (!empty($this->permissions($id_usuario)->representative)) {
            foreach ($this->permissions($id_usuario)->representative as $ut) {
                $temp['id'] = $ut->id;
                $temp['numero_ut'] = $ut->numero_ut;
                $temp['descricao'] = $ut->descricao;
                $temp['numero_coligada'] = $ut->numero_coligada;
                $temp['coligada_fantasia'] = $ut->coligada_fantasia;
                $temp['coligada_cnpj'] = $ut->coligada_cnpj;
                $temp['cidade'] = $ut->cidade;
                $temp['status'] = $ut->status;
                $temp['ano_mes_inicio'] = $ut->ano_mes_inicio;
                $temp['ano_mes_fim'] = $ut->ano_mes_fim;
                $temp['negocio_bu'] = $ut->negocio_bu;
                $temp['regional'] = $ut->regional;
                $temp['tipo_despesa'] = $ut->tipo_despesa;
                $temp['regiao'] = $ut->regiao;
                $temp['segmento'] = $ut->segmento;
                $temp['grupo_cliente'] = $ut->grupo_cliente;

                array_push($uts, $temp);
            }
        }

        return $uts;
    }
    
    public function getUtsUser() 
    {
        return $this->ut;
    }
}