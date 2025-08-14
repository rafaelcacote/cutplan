<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicoItemRequest extends FormRequest
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
            'servico_id' => ['required', 'exists:servicos,id'],
            'descricao_item' => ['required', 'string'],
            'ordem' => ['nullable', 'integer', 'min:1'],
            'opcional' => ['boolean'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'servico_id.required' => 'O serviço é obrigatório.',
            'servico_id.exists' => 'O serviço selecionado não existe.',
            'descricao_item.required' => 'A descrição do item é obrigatória.',
            'descricao_item.string' => 'A descrição deve ser um texto.',
            'ordem.integer' => 'A ordem deve ser um número inteiro.',
            'ordem.min' => 'A ordem deve ser no mínimo 1.',
            'opcional.boolean' => 'O campo opcional deve ser verdadeiro ou falso.',
        ];
    }
}
