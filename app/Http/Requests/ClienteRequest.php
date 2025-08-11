<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:150'],
            'documento' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefone' => ['nullable', 'string', 'max:50'],
            'observacoes' => ['nullable', 'string'],
            'endereco.linha1' => ['required', 'string', 'max:255'],
            'endereco.linha2' => ['nullable', 'string', 'max:255'],
            'endereco.cidade' => ['required', 'string', 'max:100'],
            'endereco.estado' => ['required', 'string', 'max:100'],
            'endereco.cep' => ['nullable', 'string', 'max:20'],
            'endereco.pais' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do cliente é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome não pode ter mais de 150 caracteres.',
            'documento.string' => 'O documento deve ser um texto.',
            'documento.max' => 'O documento não pode ter mais de 32 caracteres.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.max' => 'O email não pode ter mais de 150 caracteres.',
            'telefone.string' => 'O telefone deve ser um texto.',
            'telefone.max' => 'O telefone não pode ter mais de 50 caracteres.',
            'observacoes.string' => 'As observações devem ser um texto.',
            'endereco.linha1.required' => 'O endereço é obrigatório.',
            'endereco.linha1.string' => 'O endereço deve ser um texto.',
            'endereco.linha1.max' => 'O endereço não pode ter mais de 255 caracteres.',
            'endereco.linha2.string' => 'O complemento deve ser um texto.',
            'endereco.linha2.max' => 'O complemento não pode ter mais de 255 caracteres.',
            'endereco.cidade.required' => 'A cidade é obrigatória.',
            'endereco.cidade.string' => 'A cidade deve ser um texto.',
            'endereco.cidade.max' => 'A cidade não pode ter mais de 100 caracteres.',
            'endereco.estado.required' => 'O estado é obrigatório.',
            'endereco.estado.string' => 'O estado deve ser um texto.',
            'endereco.estado.max' => 'O estado não pode ter mais de 100 caracteres.',
            'endereco.cep.string' => 'O CEP deve ser um texto.',
            'endereco.cep.max' => 'O CEP não pode ter mais de 20 caracteres.',
            'endereco.pais.string' => 'O país deve ser um texto.',
            'endereco.pais.max' => 'O país não pode ter mais de 100 caracteres.',
        ];
    }
}
