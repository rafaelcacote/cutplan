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
        $id = $this->route('servico_item')?->id ?? $this->route('id');
        return [
            'descricao_item' => [
                'required',
                'string',
                'unique:servico_itens,descricao_item' . ($id ? ",{$id},id" : '')
            ],
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
            'descricao_item.required' => 'A descrição do item é obrigatória.',
            'descricao_item.string' => 'A descrição deve ser um texto.',
            'descricao_item.unique' => 'Já existe um item com essa descrição.',
            'ordem.integer' => 'A ordem deve ser um número inteiro.',
            'ordem.min' => 'A ordem deve ser no mínimo 1.',
            'opcional.boolean' => 'O campo opcional deve ser verdadeiro ou falso.',
        ];
    }
}
