<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Endereco;
use App\Http\Requests\ClienteRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

        $query = Cliente::with(['endereco']);

        // Filtro por nome
        if (request('nome')) {
            $query->where('nome', 'like', '%' . request('nome') . '%');
        }

        // Filtro por CPF/CNPJ
        if (request('documento')) {
            $query->where('documento', 'like', '%' . request('documento') . '%');
        }

        // Ordenação
        $sortField = request('sort', 'nome');
        $sortOrder = request('order', 'asc');
        
        $allowedSorts = ['nome', 'email', 'documento', 'telefone', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('nome', 'asc');
        }

        $perPage = min(request('per_page', 10), 50); // Máximo 50 por página
        $clientes = $query->paginate($perPage);

        return Inertia::render('clientes/Index', [
            'clientes' => $clientes,
            'filters' => [
                'nome' => request('nome'),
                'documento' => request('documento'),
                'sort' => $sortField,
                'order' => $sortOrder,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('clientes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClienteRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        // Cria o endereço
        $endereco = Endereco::create([
            'linha1' => $request->input('endereco.linha1'),
            'linha2' => $request->input('endereco.linha2'),
            'cidade' => $request->input('endereco.cidade'),
            'estado' => $request->input('endereco.estado'),
            'cep' => $request->input('endereco.cep'),
            'pais' => $request->input('endereco.pais'),
        ]);
        $validated['endereco_id'] = $endereco->id;
        unset($validated['endereco']);
        $validated['user_id'] = auth()->id(); // registra quem criou
        Cliente::create($validated);

        return to_route('clientes.index')
            ->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente): Response
    {
        $cliente->load(['endereco', 'user']);

        return Inertia::render('clientes/Show', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente): Response
    {
        $cliente->load('endereco');
        return Inertia::render('clientes/Edit', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $validated = $request->validated();
        // Atualiza o endereço
        if ($cliente->endereco) {
            $cliente->endereco->update([
                'linha1' => $request->input('endereco.linha1'),
                'linha2' => $request->input('endereco.linha2'),
                'cidade' => $request->input('endereco.cidade'),
                'estado' => $request->input('endereco.estado'),
                'cep' => $request->input('endereco.cep'),
                'pais' => $request->input('endereco.pais'),
            ]);
        }
        unset($validated['endereco']);
        $validated['user_id'] = auth()->id(); // registra quem editou
        $cliente->update($validated);

        return to_route('clientes.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return to_route('clientes.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }
}
