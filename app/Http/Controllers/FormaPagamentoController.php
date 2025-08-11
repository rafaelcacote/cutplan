<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Http\Requests\FormaPagamentoRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class FormaPagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $formasPagamento = FormaPagamento::orderBy('nome')->paginate(15);

        return Inertia::render('formas-pagamento/Index', [
            'formasPagamento' => $formasPagamento,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('formas-pagamento/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormaPagamentoRequest $request): RedirectResponse
    {
        FormaPagamento::create($request->validated());

        return to_route('formas-pagamento.index')
            ->with('success', 'Forma de pagamento criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FormaPagamento $formaPagamento): Response
    {
        return Inertia::render('formas-pagamento/Show', [
            'formaPagamento' => $formaPagamento,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormaPagamento $formaPagamento): Response
    {
        return Inertia::render('formas-pagamento/Edit', [
            'formaPagamento' => $formaPagamento,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormaPagamentoRequest $request, FormaPagamento $formaPagamento): RedirectResponse
    {
        $formaPagamento->update($request->validated());

        return to_route('formas-pagamento.index')
            ->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormaPagamento $formaPagamento): RedirectResponse
    {
        $formaPagamento->delete();

        return to_route('formas-pagamento.index')
            ->with('success', 'Forma de pagamento excluída com sucesso!');
    }
}
