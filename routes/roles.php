<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

// Rotas de API para Perfis e Permissões (evita colisão com rota de página Inertia /roles)
Route::middleware(['auth', 'verified'])
    ->prefix('api')
    ->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::put('/roles/{role}', [RoleController::class, 'update']);
        Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
        Route::get('/permissions', [RoleController::class, 'permissions']);
    });
