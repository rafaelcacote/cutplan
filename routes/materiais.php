<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;

Route::resource('materiais', MaterialController::class);
