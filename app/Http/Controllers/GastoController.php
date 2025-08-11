<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Category;
use App\Models\FormaPagamento;
use App\Http\Requests\GastoRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class GastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $gastos = Gasto::with(['categoria', 'formaPagamento'])
            ->orderBy('data_gasto', 'desc')
            ->paginate(15);

        return Inertia::render('gastos/Index', [
            'gastos' => $gastos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $categorias = Category::orderBy('nome')->get();
        $formasPagamento = FormaPagamento::orderBy('nome')->get();

        return Inertia::render('gastos/Create', [
            'categorias' => $categorias,
            'formasPagamento' => $formasPagamento,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GastoRequest $request): RedirectResponse
    {
        Gasto::create($request->validated());

        return to_route('gastos.index')
            ->with('success', 'Gasto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gasto $gasto): Response
    {
        $gasto->load(['categoria', 'formaPagamento']);

        return Inertia::render('gastos/Show', [
            'gasto' => $gasto,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gasto $gasto): Response
    {
        $categorias = Category::orderBy('nome')->get();
        $formasPagamento = FormaPagamento::orderBy('nome')->get();

        return Inertia::render('gastos/Edit', [
            'gasto' => $gasto,
            'categorias' => $categorias,
            'formasPagamento' => $formasPagamento,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GastoRequest $request, Gasto $gasto): RedirectResponse
    {
        $gasto->update($request->validated());

        return to_route('gastos.index')
            ->with('success', 'Gasto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gasto $gasto): RedirectResponse
    {
        $gasto->delete();

        return to_route('gastos.index')
            ->with('success', 'Gasto excluído com sucesso!');
    }
}
