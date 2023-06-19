<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::check()){
            $id = Auth::user()->id;
            $name = Auth::user()->name;
            $email = Auth::user()->email;

            return "ID: $id | Nome: $name | Email: $email";
        } else { 
            return 'Você não está logado no sistema';
        }
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Tarefa $tarefa)
    {
    }


    public function edit(Tarefa $tarefa)
    {
    }

    public function update(Request $request, Tarefa $tarefa)
    {
    }

    public function destroy(Tarefa $tarefa)
    {
    }
}
