<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:64|unique:materiais,sku',
            'nome' => 'required|string|max:150',
            'tipo_id' => 'required|exists:tipos_materiais,id',
            'unidade_id' => 'required|exists:unidades,id',
            'fornecedor_padrao_id' => 'nullable|exists:fornecedores,id',
            'preco_custo' => 'nullable|numeric',
            'estoque_minimo' => 'nullable|numeric',
            'controla_estoque' => 'required|boolean',
            'ativo' => 'required|boolean',
        ];
    }
}
