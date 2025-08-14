<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use App\Http\Requests\ServicoRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class ServicoController extends Controller
{
    public function index(): Response
    {
        $query = Servico::query();

        if (request('nome')) {
            $query->where('nome', 'like', '%' . request('nome') . '%');
        }
        if (request('categoria')) {
            $query->where('categoria', 'like', '%' . request('categoria') . '%');
        }
        if (request('ativo') !== null) {
            $query->where('ativo', request('ativo'));
        }

        $sortField = request('sort', 'nome');
        $sortOrder = request('order', 'asc');
        $allowedSorts = ['nome', 'preco_base', 'categoria', 'ativo', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('nome', 'asc');
        }

        $perPage = min(request('per_page', 10), 50);
        $servicos = $query->paginate($perPage);

        return Inertia::render('servicos/Index', [
            'servicos' => $servicos,
            'filters' => [
                'nome' => request('nome'),
                'categoria' => request('categoria'),
                'ativo' => request('ativo'),
                'sort' => $sortField,
                'order' => $sortOrder,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('servicos/Create');
    }

    public function store(ServicoRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        Servico::create($validated);
        return to_route('servicos.index')->with('success', 'Serviço criado com sucesso!');
    }

    public function show(Servico $servico): Response
    {
        return Inertia::render('servicos/Show', [
            'servico' => $servico,
        ]);
    }

    public function edit(Servico $servico): Response
    {
        return Inertia::render('servicos/Edit', [
            'servico' => $servico,
        ]);
    }

    public function update(ServicoRequest $request, Servico $servico): RedirectResponse
    {
        $validated = $request->validated();
        $servico->update($validated);
        return to_route('servicos.index')->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Servico $servico): RedirectResponse
    {
        if ($servico->itens()->count() > 0) {
            return to_route('servicos.index')->with('error', 'Não é possível excluir: já existem itens vinculados a este serviço.');
        }
        $servico->delete();
        return to_route('servicos.index')->with('success', 'Serviço excluído com sucesso!');
    }
}
