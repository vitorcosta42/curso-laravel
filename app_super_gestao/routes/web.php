<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return "Olá, seja bem vindo ao curso!";
// });

Route::get('/', 'PrincipalController@principal');

Route::get('/sobre-nos','SobreNosController@sobrenos');

Route::get('/contato','ContatoController@contato');

