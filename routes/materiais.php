<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;

// Ajuste de parâmetro para evitar {materiai} e usar {material}
Route::resource('materiais', MaterialController::class)
	->parameters(['materiais' => 'material']);
