<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::paginate(10);
        return view(
            'app.cliente.index',
            [
                'clientes' => $clientes,
                'request' => $request->all()
            ]
        );
    }

    public function create()
    {
        return view('app.cliente.create');
    }

    public function store(Request $request)
    {
        $regras = [
            'nome' => 'required|min:3|max:40'
        ];

        $feedback = [
            'required' => 'O campo :attribute deve ser preenchido',
            'nome.min' => 'O campo nome de ter no mínimo 3 caracteres',
            'nome.max' => 'O campo nome de ter no máximo 40 caracteres',
        ];
        $request->validate($regras,$feedback);

        $cliente = new Cliente();
        $cliente->nome = $request->get('nome');
        $cliente->save();
        return redirect()->route('cliente.index');
    }

    public function show(string $id)
    {
    }

    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}
