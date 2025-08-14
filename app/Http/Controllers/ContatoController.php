<?php

namespace App\Http\Controllers;

use App\Models\Contato;
use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Http\Requests\ContatoRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class ContatoController extends Controller
{
    public function index(): Response
    {
        $query = Contato::with(['cliente', 'fornecedor', 'user']);

        if (request('nome')) {
            $query->where('nome', 'like', '%' . request('nome') . '%');
        }
        if (request('email')) {
            $query->where('email', 'like', '%' . request('email') . '%');
        }
        if (request('telefone')) {
            $query->where('telefone', 'like', '%' . request('telefone') . '%');
        }
        if (request('cargo')) {
            $query->where('cargo', 'like', '%' . request('cargo') . '%');
        }
        if (request('cliente_id')) {
            $query->where('cliente_id', request('cliente_id'));
        }
        if (request('fornecedor_id')) {
            $query->where('fornecedor_id', request('fornecedor_id'));
        }

        $sortField = request('sort', 'nome');
        $sortOrder = request('order', 'asc');
        $allowedSorts = ['nome', 'email', 'telefone', 'cargo', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('nome', 'asc');
        }

        $perPage = min(request('per_page', 10), 50);
        $contatos = $query->paginate($perPage);

        return Inertia::render('contatos/Index', [
            'contatos' => $contatos,
            'filters' => [
                'nome' => request('nome'),
                'email' => request('email'),
                'telefone' => request('telefone'),
                'cargo' => request('cargo'),
                'cliente_id' => request('cliente_id'),
                'fornecedor_id' => request('fornecedor_id'),
                'sort' => $sortField,
                'order' => $sortOrder,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('contatos/Create');
    }

    public function store(ContatoRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        Contato::create($validated);
        return to_route('contatos.index')->with('success', 'Contato criado com sucesso!');
    }

    public function edit(Contato $contato): Response
    {
        $contato->load(['cliente', 'fornecedor']);
        return Inertia::render('contatos/Edit', [
            'contato' => $contato,
        ]);
    }

    public function update(ContatoRequest $request, Contato $contato): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $contato->update($validated);
        return to_route('contatos.index')->with('success', 'Contato atualizado com sucesso!');
    }

    public function destroy(Contato $contato): RedirectResponse
    {
        $contato->delete();
        return to_route('contatos.index')->with('success', 'Contato excluído com sucesso!');
    }

    // Para autocomplete de clientes
    public function clientesAutocomplete()
    {
        $search = request('q');
        $clientes = Cliente::where('nome', 'like', "%$search%")
            ->select('id', 'nome')
            ->limit(20)
            ->get();
        return response()->json($clientes);
    }

    // Para autocomplete de fornecedores
    public function fornecedoresAutocomplete()
    {
        $search = request('q');
        $fornecedores = Fornecedor::where('nome', 'like', "%$search%")
            ->select('id', 'nome')
            ->limit(20)
            ->get();
        return response()->json($fornecedores);
    }
}
