<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EquipeController extends Controller
{
    public function index()
    {
        $equipes = Equipe::withCount('membros')->with('lider')->get();
    return Inertia::render('equipes/Index', [
            'equipes' => $equipes,
        ]);
    }

    public function create()
    {
        $users = User::all();
    return Inertia::render('equipes/Create', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'tipo' => 'required|string|max:20',
            'lider_user_id' => 'nullable|exists:users,id',
        ]);
        $equipe = Equipe::create($data);
        return redirect()->route('equipes.show', $equipe);
    }

    public function show(Equipe $equipe)
    {
        $equipe->load(['membros.user', 'lider']);
        return Inertia::render('equipes/Show', [
            'equipe' => $equipe,
            'users' => User::select('id','name')->orderBy('name')->get(),
        ]);
    }

    public function edit(Equipe $equipe)
    {
        $users = User::all();
    return Inertia::render('equipes/Edit', [
            'equipe' => $equipe,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Equipe $equipe)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'tipo' => 'required|string|max:20',
            'lider_user_id' => 'nullable|exists:users,id',
        ]);
        $equipe->update($data);
        return redirect()->route('equipes.show', $equipe);
    }

    public function destroy(Equipe $equipe)
    {
        $equipe->delete();
        return redirect()->route('equipes.index');
    }
}
