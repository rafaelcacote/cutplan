<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\Endereco;
use App\Http\Requests\StoreFornecedorRequest;
use App\Http\Requests\UpdateFornecedorRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $fornecedores = Fornecedor::with(['endereco', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Fornecedores/Index', [
            'fornecedores' => $fornecedores,
        ]);
    }

    public function create()
    {
        $enderecos = Endereco::all();
        return Inertia::render('Fornecedores/Create', [
            'enderecos' => $enderecos,
        ]);
    }

    public function store(StoreFornecedorRequest $request)
    {
        $validated = $request->validated();
        // Cria o endereço
        $endereco = \App\Models\Endereco::create($validated['endereco']);
        // Cria o fornecedor com o endereço vinculado e o usuário autenticado
        $fornecedor = Fornecedor::create([
            'nome' => $validated['nome'],
            'documento' => $validated['documento'] ?? null,
            'email' => $validated['email'] ?? null,
            'telefone' => $validated['telefone'] ?? null,
            'endereco_id' => $endereco->id,
            'user_id' => auth()->id(),
            'observacoes' => $validated['observacoes'] ?? null,
        ]);
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor criado com sucesso!');
    }

    public function show(Fornecedor $fornecedor)
    {
        $fornecedor->load(['endereco', 'user']);
        return Inertia::render('Fornecedores/Show', [
            'fornecedor' => $fornecedor,
        ]);
    }

    public function edit(Fornecedor $fornecedor)
    {
        $fornecedor->load('endereco');
        $enderecos = Endereco::all();
        return Inertia::render('Fornecedores/Edit', [
            'fornecedor' => $fornecedor,
            'enderecos' => $enderecos,
        ]);
    }

    public function update(UpdateFornecedorRequest $request, Fornecedor $fornecedor)
    {
        $validated = $request->validated();
        // Atualiza ou cria o endereço vinculado
        if ($fornecedor->endereco_id && $fornecedor->endereco) {
            $fornecedor->endereco->update($validated['endereco']);
        } else {
            $endereco = \App\Models\Endereco::create($validated['endereco']);
            $fornecedor->endereco_id = $endereco->id;
        }
        $fornecedor->nome = $validated['nome'];
        $fornecedor->documento = $validated['documento'] ?? null;
        $fornecedor->email = $validated['email'] ?? null;
        $fornecedor->telefone = $validated['telefone'] ?? null;
        $fornecedor->observacoes = $validated['observacoes'] ?? null;
        $fornecedor->save();
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor removido com sucesso!');
    }
}
