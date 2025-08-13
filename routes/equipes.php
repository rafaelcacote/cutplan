<?php

use App\Http\Controllers\EquipeController;
use App\Http\Controllers\MembroEquipeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('equipes', EquipeController::class);
    Route::post('equipes/{equipe}/membros', [MembroEquipeController::class, 'store'])->name('equipes.membros.store');
    Route::patch('equipes/{equipe}/membros/{membro}', [MembroEquipeController::class, 'update'])->name('equipes.membros.update');
    Route::delete('equipes/{equipe}/membros/{membro}', [MembroEquipeController::class, 'destroy'])->name('equipes.membros.destroy');
});
