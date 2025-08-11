<?php

namespace App\Http\Controllers;

use App\Models\TipoMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoMaterialController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:150|unique:tipos_materiais,nome',
        ]);

        $tipo = TipoMaterial::create(['nome' => $validated['nome']]);
        return response()->json($tipo, 201);
    }
}
