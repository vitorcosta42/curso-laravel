<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SobreNosController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PrincipalController::class, 'principal'])
    ->name('site.index')
    ->middleware('log.acesso');

Route::get('/sobre-nos', [SobreNosController::class, 'sobreNos'])->name('site.sobrenos');
Route::get('/contato', [ContatoController::class, 'contato'])->name('site.contato');
Route::post('/contato', [ContatoController::class, 'salvar'])
    ->name('site.contato');
Route::get('/login/{erro?}', [LoginController::class, 'index'])->name('site.login');
Route::post('/login', [LoginController::class, 'autenticar'])->name('site.login');

Route::middleware('autenticacao:padrao')
    ->prefix('/app')->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('app.home');
        Route::get('/sair', [LoginController::class, 'sair'])->name('app.sair');
        Route::get('/cliente', [ClienteController::class, 'index'])->name('app.cliente');
        Route::get('/fornecedor', [FornecedorController::class, 'index'])->name('app.fornecedor');
        Route::post('/fornecedor/listar', [FornecedorController::class, 'listar'])->name('app.fornecedor.listar');
        Route::get('/fornecedor/adicionar',[FornecedorController::class, 'adicionar'])->name('app.fornecedor.adicionar');
        Route::post('/fornecedor/adicionar',[FornecedorController::class, 'adicionar'])->name('app.fornecedor.adicionar');

        Route::get('/produto', [ProdutoController::class, 'index'])->name('app.produto');
    });

Route::fallback(function () {
    echo 'A rota acessada não existe. <a href="' . route('site.index') . '">clique aqui</a> para ir';
});
