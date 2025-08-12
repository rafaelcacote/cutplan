<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permissões
        $permissions = [
            'ver usuarios',
            'criar usuarios',
            'editar usuarios',
            'excluir usuarios',
            'ver dashboard',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Perfis
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $gerente = Role::firstOrCreate(['name' => 'gerente']);
        $usuario = Role::firstOrCreate(['name' => 'usuario']);

        // Atribuir permissões aos perfis
        $admin->syncPermissions($permissions);
        $gerente->syncPermissions(['ver usuarios', 'ver dashboard']);
        $usuario->syncPermissions(['ver dashboard']);

        // Exemplo: atribuir perfil admin ao primeiro usuário
        $user = User::first();
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
