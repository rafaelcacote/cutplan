<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class RolePageController extends Controller
{
    public function index()
    {
        return Inertia::render('roles/Index');
    }

    public function create()
    {
        return Inertia::render('roles/Create');
    }

    public function edit(\Spatie\Permission\Models\Role $role)
    {
        return Inertia::render('roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
            ],
        ]);
    }
}
