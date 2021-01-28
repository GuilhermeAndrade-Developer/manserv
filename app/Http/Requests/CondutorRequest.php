<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CondutorRequest extends FormRequest
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
            'id_usuario' => ['required', 'int'],
            'data_vencimento_cnh' => ['required', 'date'],
            'categoria_cnh' => ['required', 'string', 'max:2'],
            'rg' => ['required', 'string']
        ];
    }
}
