<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AprovacaoRequisicaoRequest extends FormRequest
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
            'indicador_aprovacao' => ['required', 'int'],
            'observacao' => ['required']
        ];
    }
}
