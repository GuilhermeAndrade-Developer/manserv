<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
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
            'data_inicio' => ['required', 'Date'],
            'hora_retirada' => ['required', 'int'],
            'data_fim' => ['required', 'Date'],
            'id_locadora' => ['required', 'int'],
            'cidade_retirada' => ['required', 'int'],
            'estado_retirada' => ['required', 'int'],

        ];
    }
}
