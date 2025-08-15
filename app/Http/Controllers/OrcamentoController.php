<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\Cliente;
use App\Models\ItemOrcamento;
use App\Models\Servico;
use App\Models\ServicoItem;
use App\Models\Unidade;
use App\Http\Requests\OrcamentoRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrcamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $query = Orcamento::with(['cliente', 'criadoPor']);

        // Filtros
        if (request('cliente')) {
            $query->whereHas('cliente', function ($q) {
                $q->where('nome', 'like', '%' . request('cliente') . '%');
            });
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('data_inicio')) {
            $query->whereDate('created_at', '>=', request('data_inicio'));
        }

        if (request('data_fim')) {
            $query->whereDate('created_at', '<=', request('data_fim'));
        }

        // Ordenação
        $sortField = request('sort', 'created_at');
        $sortOrder = request('order', 'desc');

        $allowedSorts = ['created_at', 'cliente_id', 'status', 'total', 'validade'];
        if (in_array($sortField, $allowedSorts)) {
            if ($sortField === 'cliente_id') {
                $query->join('clientes', 'orcamentos.cliente_id', '=', 'clientes.id')
                      ->orderBy('clientes.nome', $sortOrder)
                      ->select('orcamentos.*');
            } else {
                $query->orderBy($sortField, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = min(request('per_page', 10), 50);
        $orcamentos = $query->paginate($perPage);

        return Inertia::render('orcamentos/Index', [
            'orcamentos' => $orcamentos,
            'filters' => [
                'cliente' => request('cliente'),
                'status' => request('status'),
                'data_inicio' => request('data_inicio'),
                'data_fim' => request('data_fim'),
                'sort' => $sortField,
                'order' => $sortOrder,
                'per_page' => $perPage,
            ],
            'statusOptions' => [
                ['label' => 'Rascunho', 'value' => 'draft'],
                ['label' => 'Enviado', 'value' => 'sent'],
                ['label' => 'Aprovado', 'value' => 'approved'],
                ['label' => 'Rejeitado', 'value' => 'rejected'],
                ['label' => 'Expirado', 'value' => 'expired'],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('orcamentos/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrcamentoRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['criado_por'] = auth()->id();
        $validated['versao'] = 1;

        $orcamento = Orcamento::create($validated);

        // Criar itens se fornecidos
        if ($request->has('itens') && is_array($request->itens)) {
            foreach ($request->itens as $item) {
                $orcamento->itens()->create([
                    'servico_id' => $item['servico_id'] ?? null,
                    'servicos_itens_id' => $item['servicos_itens_id'] ?? null,
                    'descricao' => $item['descricao'],
                    'quantidade' => $item['quantidade'],
                    'unidade_id' => $item['unidade_id'] ?? null,
                    'preco_unitario' => $item['preco_unitario'],
                    'eh_servico' => $item['eh_servico'] ?? false,
                ]);
            }
        }

        return to_route('orcamentos.show', $orcamento)
            ->with('success', 'Orçamento criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Orcamento $orcamento): Response
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orcamento $orcamento): Response
    {
        $orcamento->load([
            'cliente',
            'itens.servico',
            'itens.servicoItem',
            'itens.unidade'
        ]);

        return Inertia::render('orcamentos/Edit', [
            'orcamento' => $orcamento,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrcamentoRequest $request, Orcamento $orcamento): RedirectResponse
    {
        $validated = $request->validated();

        $orcamento->update($validated);

        // Atualizar itens
        if ($request->has('itens') && is_array($request->itens)) {
            // Remover itens existentes
            $orcamento->itens()->delete();

            // Criar novos itens
            foreach ($request->itens as $item) {
                $orcamento->itens()->create([
                    'servico_id' => $item['servico_id'] ?? null,
                    'servicos_itens_id' => $item['servicos_itens_id'] ?? null,
                    'descricao' => $item['descricao'],
                    'quantidade' => $item['quantidade'],
                    'unidade_id' => $item['unidade_id'] ?? null,
                    'preco_unitario' => $item['preco_unitario'],
                    'eh_servico' => $item['eh_servico'] ?? false,
                ]);
            }
        }

        return to_route('orcamentos.show', $orcamento)
            ->with('success', 'Orçamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orcamento $orcamento): RedirectResponse
    {
        $orcamento->delete();

        return to_route('orcamentos.index')
            ->with('success', 'Orçamento excluído com sucesso!');
    }

    /**
     * API endpoints para autocomplete
     */
    public function searchClientes(Request $request)
    {
        $search = $request->get('search', '');

        $clientes = Cliente::where('nome', 'like', "%{$search}%")
            ->orWhere('documento', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nome', 'documento', 'email']);

        return response()->json($clientes);
    }

    public function searchServicos(Request $request)
    {
        $search = $request->get('search', '');

        $servicos = Servico::where('nome', 'like', "%{$search}%")
            ->orWhere('descricao', 'like', "%{$search}%")
            ->where('ativo', true)
            ->limit(10)
            ->get(['id', 'nome', 'descricao', 'preco_base']);

        return response()->json($servicos);
    }

    public function searchServicoItens(Request $request)
    {
        $search = $request->get('search', '');

        $servicoItens = ServicoItem::where('descricao_item', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'descricao_item']);

        return response()->json($servicoItens);
    }

    public function searchUnidades(Request $request)
    {
        $search = $request->get('search', '');

        $unidades = Unidade::where('nome', 'like', "%{$search}%")
            ->orWhere('codigo', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'codigo', 'nome']);

        return response()->json($unidades);
    }

    /**
     * Show the form for creating a new resource with simplified interface.
     */
    public function createSimple(): Response
    {
        return Inertia::render('orcamentos/CreateSimple');
    }

    /**
     * Store a newly created resource with simplified interface.
     */
    public function storeSimple(Request $request): RedirectResponse
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'status' => 'required|in:draft,sent,approved,rejected,expired',
            'moeda' => 'required|in:BRL,USD,EUR',
            'desconto' => 'numeric|min:0',
            'impostos' => 'numeric|min:0',
            'validade' => 'nullable|date',
            'observacoes' => 'nullable|string|max:1000',
            'servicos' => 'required|array|min:1',
            'servicos.*.nome' => 'required|string|max:255',
            'servicos.*.itens' => 'required|array|min:1',
            'servicos.*.itens.*' => 'required|string|max:500',
            'servicos.*.valor_total' => 'required|numeric|min:0',
            'servicos.*.observacoes' => 'nullable|string|max:500',
        ]);

        $orcamento = Orcamento::create([
            'cliente_id' => $request->cliente_id,
            'status' => $request->status,
            'moeda' => $request->moeda,
            'desconto' => $request->desconto ?? 0,
            'impostos' => $request->impostos ?? 0,
            'validade' => $request->validade,
            'observacoes' => $request->observacoes,
            'criado_por' => auth()->id(),
        ]);

        // Salvar cada serviço/itens em itens_orcamento
        foreach ($request->servicos as $servico) {
            $servicoId = $servico['id'] ?? null;
            $valorTotal = $servico['valor_total'] ?? 0;
            $obsServico = $servico['observacoes'] ?? null;
            if (isset($servico['itens']) && is_array($servico['itens'])) {
                foreach ($servico['itens'] as $item) {
                    // Se o item for array, pega id e descricao, senão só descricao
                    $servicoItemId = is_array($item) && isset($item['id']) ? $item['id'] : null;
                    $descricao = is_array($item) && isset($item['nome']) ? $item['nome'] : (is_string($item) ? $item : null);
                    $orcamento->itens()->create([
                        'servico_id' => $servicoId,
                        'servico_item_id' => $servicoItemId,
                        'descricao' => $descricao,
                        'quantidade' => 1,
                        'unidade_id' => null,
                        'preco_unitario' => $valorTotal,
                        'eh_servico' => true,
                        'observacoes' => $obsServico,
                    ]);
                }
            }
        }

        return redirect()->route('orcamentos.index')
            ->with('success', 'Orçamento criado com sucesso!');
    }

    /**
     * Generate PDF for the specified resource.
     */
    public function gerarPdf(Orcamento $orcamento)
    {
        // Carrega as relações necessárias
        $orcamento->load(['cliente', 'itens.servico']);

        // Monta a estrutura de serviços para o PDF
        $servicos = [];
        foreach ($orcamento->itens as $item) {
            $servicoId = $item->servico_id;
            if (!isset($servicos[$servicoId])) {
                $servicos[$servicoId] = [
                    'nome' => $item->servico ? $item->servico->nome : 'Serviço',
                    'valor_total' => 0,
                    'itens' => [],
                    'observacoes' => $item->observacoes ?? null,
                ];
            }
            $servicos[$servicoId]['itens'][] = $item->descricao;
            $servicos[$servicoId]['valor_total'] += (float) $item->preco_unitario;
        }
        $orcamento->servicos = array_values($servicos);
        $orcamento->observacoes_originais = $orcamento->observacoes;

        // Gera o PDF
        $pdf = Pdf::loadView('orcamentos.pdf', compact('orcamento'));

        // Configurações do PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);

        // Retorna o PDF para download
        return $pdf->download("orcamento_{$orcamento->id}.pdf");
    }

    /**
     * Preview PDF in browser for the specified resource.
     */
    public function visualizarPdf(Orcamento $orcamento)
    {
        // Carrega as relações necessárias
        $orcamento->load(['cliente', 'itens.servico']);

        // Monta a estrutura de serviços para o PDF
        $servicos = [];
        foreach ($orcamento->itens as $item) {
            $servicoId = $item->servico_id;
            if (!isset($servicos[$servicoId])) {
                $servicos[$servicoId] = [
                    'nome' => $item->servico ? $item->servico->nome : 'Serviço',
                    'valor_total' => 0,
                    'itens' => [],
                    'observacoes' => $item->observacoes ?? null,
                ];
            }
            $servicos[$servicoId]['itens'][] = $item->descricao;
            $servicos[$servicoId]['valor_total'] += (float) $item->preco_unitario;
        }
        $orcamento->servicos = array_values($servicos);
        $orcamento->observacoes_originais = $orcamento->observacoes;

        // Gera o PDF
        $pdf = Pdf::loadView('orcamentos.pdf', compact('orcamento'));

        // Configurações do PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);

        // Retorna o PDF inline (para visualizar no navegador)
        return $pdf->stream("orcamento_{$orcamento->id}.pdf");
    }
}
