<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EnderecoController extends Controller
{
    public function index(): Response
    {
        $enderecos = Endereco::latest()->paginate(10);
        return Inertia::render('enderecos/Index', [
            'enderecos' => $enderecos,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('enderecos/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'linha1' => 'required|string|max:150',
            'linha2' => 'nullable|string|max:150',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cep' => 'nullable|string|max:20',
            'pais' => 'nullable|string|max:100',
        ]);
        Endereco::create($validated);
        return redirect()->route('enderecos.index')->with('success', 'Endereço cadastrado com sucesso!');
    }

    public function edit(Endereco $endereco): Response
    {
        return Inertia::render('enderecos/Edit', [
            'endereco' => $endereco,
        ]);
    }

    public function update(Request $request, Endereco $endereco)
    {
        $validated = $request->validate([
            'linha1' => 'required|string|max:150',
            'linha2' => 'nullable|string|max:150',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cep' => 'nullable|string|max:20',
            'pais' => 'nullable|string|max:100',
        ]);
        $endereco->update($validated);
        return redirect()->route('enderecos.index')->with('success', 'Endereço atualizado com sucesso!');
    }

    public function destroy(Endereco $endereco)
    {
        $endereco->delete();
        return redirect()->route('enderecos.index')->with('success', 'Endereço removido com sucesso!');
    }
}
