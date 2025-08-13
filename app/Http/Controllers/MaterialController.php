<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Fornecedor;
use App\Models\Unidade;
use App\Models\TipoMaterial;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;

class MaterialController extends Controller
{
    public function index()
    {
        $materiais = Material::with(['tipo', 'unidade', 'fornecedorPadrao'])->paginate(15);
    return inertia('materiais/Index', compact('materiais'));
    }

    public function create()
    {
        $fornecedores = Fornecedor::all();
        $unidades = Unidade::all();
        $tiposmaterial = TipoMaterial::all();
    return inertia('materiais/Create', [
            'fornecedores' => $fornecedores,
            'unidades' => $unidades,
            'tiposmaterial' => $tiposmaterial,
        ]);
    }

    public function store(StoreMaterialRequest $request)
    {
        $material = Material::create($request->validated());
        return redirect()->route('materiais.index')->with('success', 'Material cadastrado com sucesso!');
    }

    public function show(Material $material)
    {
        $material->load(['tipo', 'unidade', 'fornecedorPadrao']);
    return inertia('materiais/Show', compact('material'));
    }

    public function edit(Material $material)
    {
        $material->load(['tipo', 'unidade', 'fornecedorPadrao']);

        $fornecedores = Fornecedor::all();
        $unidades = Unidade::all();
        $tiposmaterial = TipoMaterial::all();
    return inertia('materiais/Edit', [
            'material' => $material,
            'fornecedores' => $fornecedores,
            'unidades' => $unidades,
            'tiposmaterial' => $tiposmaterial,
        ]);
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
