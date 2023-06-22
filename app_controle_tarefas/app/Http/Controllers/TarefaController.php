<?php

namespace App\Http\Controllers;

use App\Exports\TarefasExport;
use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TarefaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = auth()->user()->id;
        $tarefas = Tarefa::where('user_id', $user_id)->paginate(1);
        return view('tarefa.index', ['tarefas' => $tarefas]);
    }

    public function create()
    {
        return view('tarefa.create');
    }

    public function store(Request $request)
    {
        $dados = $request->all('tarefa', 'data_limite_conclusao');
        $dados['user_id'] = auth()->user()->id;

        $tarefa = Tarefa::create($dados);

        $destinatario = auth()->user()->email;
        Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));
        return redirect()->route(
            'tarefa.show',
            ['tarefa' => $tarefa->id]
        );
    }

    public function show(Tarefa $tarefa)
    {
        return view('tarefa.show', ['tarefa' => $tarefa]);
    }

    public function edit(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;
        $tarefa->user_id;
        if (!$tarefa->user_id == $user_id) {
            return view('acesso-negado');
        }
        return view('tarefa.edit', ['tarefa' => $tarefa]);
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;
        if (!$tarefa->user_id == $user_id) {
            $tarefa->update($request->all());
            return view('acesso-negado');
        }
        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    public function destroy(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;
        if (!$tarefa->user_id == $user_id) {
            return view('acesso-negado');
        }
        $tarefa->delete();
        return redirect()->route('tarefa.index');
    }
    public function exportacao($extensao) {        
        $arquivo = 'lista_de_tarefas.' . $extensao;
         return Excel::download(new TarefasExport, $arquivo);
    }
}
