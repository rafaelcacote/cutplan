<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GastoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data_gasto' => ['required', 'date'],
            'valor' => ['required', 'numeric', 'min:0.01'],
            'descricao' => ['required', 'string', 'max:255'],
            'categoria_id' => ['required', 'exists:categories,id'],
            'forma_pagamento_id' => ['required', 'exists:formas_pagamento,id'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'data_gasto.required' => 'A data do gasto é obrigatória.',
            'data_gasto.date' => 'A data do gasto deve ser uma data válida.',
            'valor.required' => 'O valor do gasto é obrigatório.',
            'valor.numeric' => 'O valor deve ser um número.',
            'valor.min' => 'O valor deve ser maior que zero.',
            'descricao.required' => 'A descrição do gasto é obrigatória.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'descricao.max' => 'A descrição não pode ter mais de 255 caracteres.',
            'categoria_id.required' => 'A categoria é obrigatória.',
            'categoria_id.exists' => 'A categoria selecionada não é válida.',
            'forma_pagamento_id.required' => 'A forma de pagamento é obrigatória.',
            'forma_pagamento_id.exists' => 'A forma de pagamento selecionada não é válida.',
        ];
    }
}
