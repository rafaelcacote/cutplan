<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class UsuarioPageController extends Controller
{
    public function index()
    {
        return Inertia::render('usuarios/Index');
    }
}
