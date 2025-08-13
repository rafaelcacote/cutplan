<?php

namespace App\Http\Controllers;

use App\Models\MembroEquipe;
use App\Models\Equipe;
use App\Models\User;
use Illuminate\Http\Request;

class MembroEquipeController extends Controller
{
    public function store(Request $request, Equipe $equipe)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'papel' => 'nullable|string|max:50',
        ]);
        $data['equipe_id'] = $equipe->id;
        MembroEquipe::create($data);
        return back();
    }

    public function update(Request $request, Equipe $equipe, MembroEquipe $membro)
    {
        $data = $request->validate([
            'papel' => 'nullable|string|max:50',
        ]);
        $membro->update($data);
        return back();
    }

    public function destroy(Equipe $equipe, MembroEquipe $membro)
    {
        $membro->delete();
        return back();
    }
}
