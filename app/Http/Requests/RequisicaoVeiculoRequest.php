<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequisicaoVeiculoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'categoria_veiculo' => ['required', 'int'],
            'id_condutor' => ['required', 'int'],
            'data_retirada' => ['required', 'date'],
            'prazo_locacao' => ['required', 'int'],
            'data_devolucao' => ['required', 'date'],
            'cidade_retirada' => ['required', 'int'],
            'estado_retirada' => ['required', 'int'],
            'local_rodagem' => ['required'],
            'km_mensal' => ['required', 'int'],
            'limite_cartao_combustivel' => ['required', 'numeric']
        ];
    }
}
