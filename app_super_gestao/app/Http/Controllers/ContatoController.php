<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteContato;
use App\MotivoContato;

class ContatoController extends Controller

{
    public function contato(Request $request)
    {

        $motivo_contatos = MotivoContato::all();

        return view('site.contato', ['titulo' => 'Contato (teste)', 'motivo_contatos' => $motivo_contatos]);
    }

    public function salvar(Request $request)
    {
        $schema =   [
            'nome' => 'required|min:3|max:40|unique:site_contatos',
            'telefone' => 'required',
            'email' => 'required|email',
            'motivo_contatos_id' => 'required',
            'mensagem' => 'required|max:2000'
        ];

        $messages =   [
            'nome.required' => 'O campo nome precisa ser preenchido.',
            'nome.min' => 'O campo nome precisa ter no mínimo 3 caracteres.',
            'nome.max' => 'O campo nome deve ter no máximo 40 caracteres.',
            'nome.unique' => 'O nome informado já está em uso.',

            'telefone.required' => 'O campo telefone precisa ser preenchido.',

            'motivo_contatos_id.required' => 'Selecione um motivo para o contato.',

            'email' => 'O campo precisa ser preenchido com um email válido.',

            'mensagem.max' => 'O campo mensagem deve ter no máximo 2000 caracteres.',

            'required' => 'O campo :attribute precisa ser preenchido.'
        ];



        //realizar a validação dos dados do formulário recebidos no request
        $request->validate(
            $schema,
            $messages
        );
        SiteContato::create($request->all());
        return redirect()->route('site.index');
    }
}
