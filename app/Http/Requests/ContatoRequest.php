<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContatoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefone' => ['nullable', 'string', 'max:50'],
            'cargo' => ['nullable', 'string', 'max:100'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'fornecedor_id' => ['nullable', 'exists:fornecedores,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do contato é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'cliente_id.exists' => 'Cliente inválido.',
            'fornecedor_id.exists' => 'Fornecedor inválido.',
        ];
    }
}
