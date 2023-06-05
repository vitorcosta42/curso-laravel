<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function index()
    {
        $erro = $request->get('erro');
        return view('site.login', ['titulo' => 'Login','erro'=>$erro]);
    }

    public function autenticar(Request $request)
    {
        $regras = [
            'usuario' => 'email',
            'senha' => 'required'
        ];

        $feedback = [
            'usuario.email' => 'O campo usuário (e-mail) é obrigatório',
            'senha.required' => 'O campo senha é obrigatório'
        ];

        $request->validate($regras, $feedback);
        $email = $request->get('usuario');
        $password = $request->get('senha');

        // Model User
        $user = new User();
        $usuario = $user
        ->where('email', $email)
        ->where('password', $password)
        ->first();

        if(isset($usuario->name)) { 
            echo 'Usuário existe';
    } else { 
        return redirect()->route('site.login',
        ['erro'=>1]);
    }

    }
}