<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrcamentoRequest extends FormRequest
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
            'cliente_id' => ['required', 'exists:clientes,id'],
            'status' => ['nullable', 'string', 'in:draft,sent,approved,rejected,expired'],
            'moeda' => ['nullable', 'string', 'max:8'],
            'desconto' => ['nullable', 'numeric', 'min:0'],
            'impostos' => ['nullable', 'numeric', 'min:0'],
            'validade' => ['nullable', 'date', 'after_or_equal:today'],
            'observacoes' => ['nullable', 'string'],

            // Validação para itens
            'itens' => ['nullable', 'array'],
            'itens.*.servico_id' => ['nullable', 'exists:servicos,id'],
            'itens.*.servicos_itens_id' => ['nullable', 'exists:servico_itens,id'],
            'itens.*.descricao' => ['required', 'string', 'max:255'],
            'itens.*.quantidade' => ['required', 'numeric', 'min:0.0001'],
            'itens.*.unidade_id' => ['nullable', 'exists:unidades,id'],
            'itens.*.preco_unitario' => ['required', 'numeric', 'min:0'],
            'itens.*.eh_servico' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'cliente_id.required' => 'O cliente é obrigatório.',
            'cliente_id.exists' => 'O cliente selecionado não existe.',
            'status.in' => 'O status deve ser: rascunho, enviado, aprovado, rejeitado ou expirado.',
            'moeda.max' => 'A moeda não pode ter mais de 8 caracteres.',
            'desconto.numeric' => 'O desconto deve ser um valor numérico.',
            'desconto.min' => 'O desconto não pode ser negativo.',
            'impostos.numeric' => 'Os impostos devem ser um valor numérico.',
            'impostos.min' => 'Os impostos não podem ser negativos.',
            'validade.date' => 'A validade deve ser uma data válida.',
            'validade.after_or_equal' => 'A validade deve ser hoje ou uma data futura.',
            'observacoes.string' => 'As observações devem ser um texto.',

            // Mensagens para itens
            'itens.array' => 'Os itens devem ser uma lista.',
            'itens.*.servico_id.exists' => 'O serviço selecionado não existe.',
            'itens.*.servicos_itens_id.exists' => 'O item de serviço selecionado não existe.',
            'itens.*.descricao.required' => 'A descrição do item é obrigatória.',
            'itens.*.descricao.string' => 'A descrição deve ser um texto.',
            'itens.*.descricao.max' => 'A descrição não pode ter mais de 255 caracteres.',
            'itens.*.quantidade.required' => 'A quantidade é obrigatória.',
            'itens.*.quantidade.numeric' => 'A quantidade deve ser um número.',
            'itens.*.quantidade.min' => 'A quantidade deve ser maior que zero.',
            'itens.*.unidade_id.exists' => 'A unidade selecionada não existe.',
            'itens.*.preco_unitario.required' => 'O preço unitário é obrigatório.',
            'itens.*.preco_unitario.numeric' => 'O preço unitário deve ser um número.',
            'itens.*.preco_unitario.min' => 'O preço unitário não pode ser negativo.',
            'itens.*.eh_servico.boolean' => 'O campo "é serviço" deve ser verdadeiro ou falso.',
        ];
    }

    /**
     * Get custom attribute names for validation.
     */
    public function attributes(): array
    {
        return [
            'cliente_id' => 'cliente',
            'validade' => 'data de validade',
            'observacoes' => 'observações',
            'itens.*.descricao' => 'descrição',
            'itens.*.quantidade' => 'quantidade',
            'itens.*.preco_unitario' => 'preço unitário',
            'itens.*.eh_servico' => 'é serviço',
        ];
    }
}
