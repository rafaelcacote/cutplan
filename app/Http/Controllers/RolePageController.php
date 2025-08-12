<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class RolePageController extends Controller
{
    public function index()
    {
        return Inertia::render('roles/Index');
    }
}
