<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFornecedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:150',
            'documento' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:150',
            'telefone' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
            'endereco.linha1' => 'required|string|max:255',
            'endereco.linha2' => 'nullable|string|max:255',
            'endereco.cidade' => 'required|string|max:100',
            'endereco.estado' => 'required|string|max:50',
            'endereco.cep' => 'nullable|string|max:20',
            'endereco.pais' => 'nullable|string|max:50',
        ];
    }
}
