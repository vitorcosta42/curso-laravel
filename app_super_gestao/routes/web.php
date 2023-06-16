<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoProdutoController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProdutoDetalheController;
use App\Http\Controllers\SobreNosController;
use Illuminate\Support\Facades\Route;

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
        Route::get('/produto', [ProdutoController::class, 'index'])->name('app.produto');
        Route::resource('produto',ProdutoController::class);
        Route::resource('produto-detalhe',ProdutoDetalheController::class);
        Route::resource('cliente',ClienteController::class);
      
        Route::resource('pedido',PedidoController::class);
        Route::resource('pedido-produto',PedidoProdutoController::class);

        Route::get('pedido-produto/create/{pedido}', [PedidoProdutoController::class,'create']);
        Route::post('pedido-produto/create/{pedido}', [PedidoProdutoController::class,'store']);
     
    }) 
      ->prefix('/app/fornecedor/')->group(function(){
        Route::get('/', [FornecedorController::class, 'index'])->name('app.fornecedor');
        Route::post('listar', [FornecedorController::class, 'listar'])->name('app.fornecedor.listar');
        Route::get('listar', [FornecedorController::class, 'listar'])->name('app.fornecedor.listar');
        Route::get('adicionar',[FornecedorController::class, 'adicionar'])->name('app.fornecedor.adicionar');
        Route::post('adicionar',[FornecedorController::class, 'adicionar'])->name('app.fornecedor.adicionar');
        Route::get('editar/{id}/{msg?}',[FornecedorController::class, 'editar'])->name('app.fornecedor.editar');
        Route::get('excluir/{id}',[FornecedorController::class, 'excluir'])->name('app.fornecedor.excluir');
    });

Route::fallback(function () {
    echo 'A rota acessada não existe. <a href="' . route('site.index') . '">clique aqui</a> para ir';
});

