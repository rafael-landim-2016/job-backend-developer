<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//ROTAS DO CRUD (LISTAR, ADICIONAR, ATUALIZAR E EXCLUIR)
Route::resource('products', ProductsController::class)->except(['create']);
//ROTA PARA FILTRAR PRODUTOS
Route::put('products', [ProductsController::class, 'index']);
//ROTA PARA PEGAR OS PRODUTOS QUE NÃO CONTÉM IMAGEM
Route::get('get-products-without-photo', [ProductsController::class, 'withoutPhoto']);

