<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;

Route::get('/clientes', [ClienteController::class, 'listar']);
Route::post('/clientes', [ClienteController::class, 'insertar']);
Route::put('clientes/{id}', [ClienteController::class, 'actualizar']);
Route::delete('/clientes/{id}', [ClienteController::class, 'eliminar']);
Route::post('/categorias', [CategoriaController::class, 'insertar']);
Route::post('/productos', [ProductoController::class, 'insertar']);
Route::get('/productos', [ProductoController::class, 'listarProductos']);
Route::post('/pedidos/completo', [PedidoController::class, 'insertarCompleto']);
Route::get('/pedidos', [PedidoController::class, 'listarPedidos']);
