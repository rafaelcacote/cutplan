<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class UsuarioPageController extends Controller
{
    public function index()
    {
        return Inertia::render('usuarios/Index');
    }

    public function create()
    {
        return Inertia::render('usuarios/Create');
    }

    public function edit(\App\Models\User $user)
    {
        return Inertia::render('usuarios/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles()->pluck('name'),
            ],
        ]);
    }
}
