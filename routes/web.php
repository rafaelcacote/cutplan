<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\ServicoItemController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\RolePageController;
use App\Http\Controllers\UsuarioPageController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/usuarios.php';
require __DIR__.'/roles.php';
require __DIR__.'/equipes.php';
use Inertia\Inertia;


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->get('/roles', [RolePageController::class, 'index'])->name('roles.index');
Route::middleware(['auth', 'verified'])->get('/roles/create', [RolePageController::class, 'create'])->name('roles.create');
Route::middleware(['auth', 'verified'])->get('/roles/{role}/edit', [RolePageController::class, 'edit'])->name('roles.edit');
Route::middleware(['auth', 'verified'])->get('/usuarios', [UsuarioPageController::class, 'index'])->name('usuarios.index');
Route::middleware(['auth', 'verified'])->get('/usuarios/create', [UsuarioPageController::class, 'create'])->name('usuarios.create');
Route::middleware(['auth', 'verified'])->get('/usuarios/{user}/edit', [UsuarioPageController::class, 'edit'])->name('usuarios.edit');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('clientes', ClienteController::class);
    Route::resource('orcamentos', OrcamentoController::class)->except('show');

    // Nova rota para o formulário simples de orçamentos
    Route::get('orcamentos-simples/create', [OrcamentoController::class, 'createSimple'])->name('orcamentos.create-simple');
    Route::post('orcamentos-simples', [OrcamentoController::class, 'storeSimple'])->name('orcamentos.store-simple');

    // Rotas para PDF dos orçamentos
    Route::get('orcamentos/{orcamento}/pdf', [OrcamentoController::class, 'gerarPdf'])->name('orcamentos.pdf');
    Route::get('orcamentos/{orcamento}/visualizar-pdf', [OrcamentoController::class, 'visualizarPdf'])->name('orcamentos.visualizar-pdf');

    // Rotas de autocomplete para orçamentos
    Route::get('autocomplete/servicos', [OrcamentoController::class, 'searchServicos'])->name('autocomplete.servicos');
    Route::get('autocomplete/servico-itens', [OrcamentoController::class, 'searchServicoItens'])->name('autocomplete.servico-itens');
    Route::get('autocomplete/unidades', [OrcamentoController::class, 'searchUnidades'])->name('autocomplete.unidades');

    Route::resource('servicos', ServicoController::class);
    Route::resource('servico-itens', ServicoItemController::class)->parameters([
        'servico-itens' => 'servicoItem'
    ]);
    Route::get('autocomplete/servicos-for-items', [ServicoItemController::class, 'searchServicos'])->name('autocomplete.servicos-for-items');
    Route::resource('fornecedores', FornecedorController::class)->parameters([
        'fornecedores' => 'fornecedor'
    ]);
    Route::resource('materiais', MaterialController::class)->parameters([
        'materiais' => 'material'
    ]);
    Route::resource('enderecos', EnderecoController::class);
    Route::post('tipos-materiais', [\App\Http\Controllers\TipoMaterialController::class, 'store'])->name('tipos-materiais.store');

    // CRUD de contatos
    Route::resource('contatos', \App\Http\Controllers\ContatoController::class);
    // Autocomplete para clientes e fornecedores
    Route::get('autocomplete/clientes', [\App\Http\Controllers\ContatoController::class, 'clientesAutocomplete'])->name('autocomplete.clientes');
    Route::get('autocomplete/fornecedores', [\App\Http\Controllers\ContatoController::class, 'fornecedoresAutocomplete'])->name('autocomplete.fornecedores');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
