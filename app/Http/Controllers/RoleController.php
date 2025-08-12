<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);
        $role = Role::create(['name' => $data['name']]);
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return response()->json(['success' => true]);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);
        $role->name = $data['name'];
        $role->save();
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return response()->json(['success' => true]);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['success' => true]);
    }

    public function permissions()
    {
        $permissions = Permission::all(['name', 'id']);
        return response()->json($permissions);
    }
}
