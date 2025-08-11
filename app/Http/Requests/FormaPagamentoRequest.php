<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormaPagamentoRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255', 'unique:formas_pagamento,nome,' . $this->route('formas_pagamento')?->id],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da forma de pagamento é obrigatório.',
            'nome.string' => 'O nome da forma de pagamento deve ser um texto.',
            'nome.max' => 'O nome da forma de pagamento não pode ter mais de 255 caracteres.',
            'nome.unique' => 'Já existe uma forma de pagamento com este nome.',
        ];
    }
}
