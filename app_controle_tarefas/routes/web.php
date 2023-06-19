<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TarefaController;
use App\Mail\MensagemTesteMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('tarefa',TarefaController::class);
Route::get('/mensagem-teste',
function(){
    return new MensagemTesteMail();
    // Mail::to('vitorcursolaravel@gmail.com')->send(
    //     new MensagemTesteMail());
    //     return 'E-mail enviado com sucesso!';
    
    }

);