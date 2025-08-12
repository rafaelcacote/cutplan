<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ];
        });
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles' => 'array',
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }
        return response()->json(['success' => true]);
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|min:6',
            'roles' => 'array',
        ]);
        $usuario->name = $data['name'];
        $usuario->email = $data['email'];
        if (!empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }
        $usuario->save();
        if (isset($data['roles'])) {
            $usuario->syncRoles($data['roles']);
        }
        return response()->json(['success' => true]);
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return response()->json(['success' => true]);
    }

    public function roles()
    {
        $roles = Role::all(['name', 'id']);
        return response()->json($roles);
    }
}
