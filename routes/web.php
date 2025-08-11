<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\FornecedorController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


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
