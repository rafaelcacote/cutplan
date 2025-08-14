<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
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
        $materialId = $this->route('material') ? $this->route('material')->id : null;
        
        return [
            'sku' => ['required', 'string', 'max:64', 'unique:materiais,sku,' . $materialId],
            'nome' => ['required', 'string', 'max:150'],
            'tipo_id' => ['required', 'exists:tipos_materiais,id'],
            'unidade_id' => ['required', 'exists:unidades,id'],
            'fornecedor_padrao_id' => ['nullable', 'exists:fornecedores,id'],
            'preco_custo' => ['nullable', 'numeric', 'min:0', 'max:99999999.9999'],
            'estoque_minimo' => ['nullable', 'numeric', 'min:0', 'max:9999999999999999.9999'],
            'controla_estoque' => ['required', 'boolean'],
            'ativo' => ['required', 'boolean'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'sku.required' => 'O SKU é obrigatório.',
            'sku.string' => 'O SKU deve ser um texto.',
            'sku.max' => 'O SKU não pode ter mais de 64 caracteres.',
            'sku.unique' => 'Este SKU já está sendo utilizado por outro material.',
            'nome.required' => 'O nome do material é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome não pode ter mais de 150 caracteres.',
            'tipo_id.required' => 'O tipo de material é obrigatório.',
            'tipo_id.exists' => 'O tipo de material selecionado é inválido.',
            'unidade_id.required' => 'A unidade é obrigatória.',
            'unidade_id.exists' => 'A unidade selecionada é inválida.',
            'fornecedor_padrao_id.exists' => 'O fornecedor padrão selecionado é inválido.',
            'preco_custo.numeric' => 'O preço de custo deve ser um número.',
            'preco_custo.min' => 'O preço de custo não pode ser negativo.',
            'preco_custo.max' => 'O preço de custo é muito alto.',
            'estoque_minimo.numeric' => 'O estoque mínimo deve ser um número.',
            'estoque_minimo.min' => 'O estoque mínimo não pode ser negativo.',
            'estoque_minimo.max' => 'O estoque mínimo é muito alto.',
            'controla_estoque.required' => 'É obrigatório informar se controla estoque.',
            'controla_estoque.boolean' => 'O campo controla estoque deve ser verdadeiro ou falso.',
            'ativo.required' => 'É obrigatório informar se o material está ativo.',
            'ativo.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
        ];
    }
}
