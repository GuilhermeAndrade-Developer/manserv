<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrepostoRequest extends FormRequest
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
            'id' => ['required', 'int'],
            'cpf' => ['required', 'cpf', 'string', 'min:11', 'max:12'],
            'nome' => ['required', 'string'],
            'status' => ['required', 'string', 'max:1'],
            'data_cadastro' => ['required', 'date'],
            'data_final' => ['required', 'date'],
        ];
    }
}
