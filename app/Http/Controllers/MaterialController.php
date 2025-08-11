<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Fornecedor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;

class MaterialController extends Controller
{
    public function index()
    {
        $materiais = Material::with(['tipo', 'unidade', 'fornecedorPadrao'])->paginate(15);
        return inertia('Materiais/Index', compact('materiais'));
    }

    public function create()
    {
        $fornecedores = Fornecedor::all();
        // tipos e unidades: adicionar quando os models existirem
        return inertia('Materiais/Create', compact('fornecedores'));
    }

    public function store(StoreMaterialRequest $request)
    {
        $material = Material::create($request->validated());
        return redirect()->route('materiais.index')->with('success', 'Material cadastrado com sucesso!');
    }

    public function show(Material $material)
    {
        $material->load(['tipo', 'unidade', 'fornecedorPadrao']);
        return inertia('Materiais/Show', compact('material'));
    }

    public function edit(Material $material)
    {
        $material->load(['tipo', 'unidade', 'fornecedorPadrao']);
        $fornecedores = Fornecedor::all();
        // tipos e unidades: adicionar quando os models existirem
        return inertia('Materiais/Edit', compact('material', 'fornecedores'));
    }

    public function update(UpdateMaterialRequest $request, Material $material)
    {
        $material->update($request->validated());
        return redirect()->route('materiais.index')->with('success', 'Material atualizado com sucesso!');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('materiais.index')->with('success', 'Material removido com sucesso!');
    }
}
