<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// Rotas de API para Usuários (evita colisão com rotas de página Inertia)
Route::middleware(['auth', 'verified'])
    ->prefix('api')
    ->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::post('/usuarios', [UsuarioController::class, 'store']);
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update']);
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy']);
        // Mantido temporariamente caso algum código ainda consuma esse endpoint para opções de roles.
        Route::get('/usuarios/roles', [UsuarioController::class, 'roles']);
    });
