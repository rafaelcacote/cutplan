<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'descricao' => ['nullable', 'string'],
            'preco_base' => ['nullable', 'numeric', 'min:0'],
            'categoria' => ['nullable', 'string', 'max:50'],
            'ativo' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do serviço é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 100 caracteres.',
            'preco_base.numeric' => 'O preço base deve ser um valor numérico.',
            'preco_base.min' => 'O preço base não pode ser negativo.',
            'categoria.max' => 'A categoria não pode ter mais de 50 caracteres.',
        ];
    }
}
