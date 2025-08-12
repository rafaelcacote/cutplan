<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\RolePageController;
use App\Http\Controllers\UsuarioPageController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/usuarios.php';
require __DIR__.'/roles.php';
use Inertia\Inertia;


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->get('/roles', [RolePageController::class, 'index'])->name('roles.index');
Route::middleware(['auth', 'verified'])->get('/usuarios', [UsuarioPageController::class, 'index'])->name('usuarios.index');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('clientes', ClienteController::class);
    Route::resource('fornecedores', FornecedorController::class)->parameters([
        'fornecedores' => 'fornecedor'
    ]);
    Route::resource('enderecos', EnderecoController::class);
    Route::post('tipos-materiais', [\App\Http\Controllers\TipoMaterialController::class, 'store'])->name('tipos-materiais.store');
    require __DIR__.'/materiais.php';
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
