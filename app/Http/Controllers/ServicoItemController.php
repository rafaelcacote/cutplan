<?php

namespace App\Http\Controllers;

use App\Models\ServicoItem;
use App\Models\Servico;
use App\Http\Requests\ServicoItemRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServicoItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
    $query = ServicoItem::query();

        // Filtro por descrição
        if (request('descricao_item')) {
            $query->where('descricao_item', 'like', '%' . request('descricao_item') . '%');
        }

    // Filtro por serviço removido

        // Filtro por opcional
        if (request('opcional') !== null && request('opcional') !== '') {
            $query->where('opcional', request('opcional') === '1');
        }

        // Ordenação
        $sortField = request('sort', 'ordem');
        $sortOrder = request('order', 'asc');

        $allowedSorts = ['descricao_item', 'ordem', 'opcional', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('ordem', 'asc')->orderBy('id', 'desc');
        }

        $perPage = min(request('per_page', 10), 50); // Máximo 50 por página
        $servicoItens = $query->paginate($perPage);

        return Inertia::render('servico-itens/Index', [
            'servicoItens' => $servicoItens,
            'filters' => [
                'descricao_item' => request('descricao_item'),
                'servico_nome' => request('servico_nome'),
                'opcional' => request('opcional'),
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
        return Inertia::render('servico-itens/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServicoItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        ServicoItem::create($validated);

        return to_route('servico-itens.index')
            ->with('success', 'Item de serviço criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServicoItem $servicoItem): Response
    {


        return Inertia::render('servico-itens/Show', [
            'servicoItem' => $servicoItem,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServicoItem $servicoItem): Response
    {


        return Inertia::render('servico-itens/Edit', [
            'servicoItem' => $servicoItem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServicoItemRequest $request, ServicoItem $servicoItem): RedirectResponse
    {
        $validated = $request->validated();

        $servicoItem->update($validated);

        return to_route('servico-itens.index')
            ->with('success', 'Item de serviço atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServicoItem $servicoItem): RedirectResponse
    {
        $servicoItem->delete();

        return to_route('servico-itens.index')
            ->with('success', 'Item de serviço excluído com sucesso!');
    }

    /**
     * Search servicos for AutoComplete
     */
    public function searchServicos(Request $request)
    {
        $query = $request->get('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $servicos = Servico::where('nome', 'like', "%{$query}%")
            ->where('ativo', true)
            ->select('id', 'nome')
            ->limit(20)
            ->get()
            ->map(function ($servico) {
                return [
                    'value' => $servico->id,
                    'label' => $servico->nome,
                ];
            });

        return response()->json($servicos);
    }
}
