<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\TipoMaterial;
use App\Models\Unidade;
use App\Models\Fornecedor;
use App\Http\Requests\MaterialRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $query = Material::with(['tipo', 'unidade', 'fornecedorPadrao']);

        // Filtro por nome
        if (request('nome')) {
            $query->where('nome', 'like', '%' . request('nome') . '%');
        }

        // Filtro por SKU
        if (request('sku')) {
            $query->where('sku', 'like', '%' . request('sku') . '%');
        }

        // Filtro por tipo
        if (request('tipo_id')) {
            $query->where('tipo_id', request('tipo_id'));
        }

        // Filtro por ativo
        if (request('ativo') !== null) {
            $query->where('ativo', request('ativo'));
        }

        // Ordenação
        $sortField = request('sort', 'nome');
        $sortOrder = request('order', 'asc');
        
        $allowedSorts = ['nome', 'sku', 'preco_custo', 'estoque_minimo', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('nome', 'asc');
        }

        $perPage = min(request('per_page', 10), 50); // Máximo 50 por página
        $materiais = $query->paginate($perPage);

        // Dados para os filtros
        $tipos = TipoMaterial::orderBy('nome')->get();

        return Inertia::render('materiais/Index', [
            'materiais' => $materiais,
            'tipos' => $tipos,
            'filters' => [
                'nome' => request('nome'),
                'sku' => request('sku'),
                'tipo_id' => request('tipo_id'),
                'ativo' => request('ativo'),
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
        $tipos = TipoMaterial::orderBy('nome')->get();
        $unidades = Unidade::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();

        return Inertia::render('materiais/Create', [
            'tipos' => $tipos,
            'unidades' => $unidades,
            'fornecedores' => $fornecedores,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MaterialRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id(); // registra quem criou
        
        Material::create($validated);

        return to_route('materiais.index')
            ->with('success', 'Material criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material): Response
    {
        $material->load(['tipo', 'unidade', 'fornecedorPadrao', 'user']);

        return Inertia::render('materiais/Show', [
            'material' => $material,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material): Response
    {
        $tipos = TipoMaterial::orderBy('nome')->get();
        $unidades = Unidade::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();

        return Inertia::render('materiais/Edit', [
            'material' => $material,
            'tipos' => $tipos,
            'unidades' => $unidades,
            'fornecedores' => $fornecedores,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MaterialRequest $request, Material $material): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id(); // registra quem editou
        
        $material->update($validated);

        return to_route('materiais.index')
            ->with('success', 'Material atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material): RedirectResponse
    {
        $material->delete();

        return to_route('materiais.index')
            ->with('success', 'Material excluído com sucesso!');
    }
}
