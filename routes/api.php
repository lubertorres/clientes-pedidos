<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\DashboardController;

Route::get('/clientes', [ClienteController::class, 'listar']);
Route::post('/clientes', [ClienteController::class, 'insertar']);
Route::put('clientes/{id}', [ClienteController::class, 'actualizar']);
Route::delete('/clientes/{id}', [ClienteController::class, 'eliminar']);
Route::post('/categorias', [CategoriaController::class, 'insertar']);
Route::post('/productos', [ProductoController::class, 'insertar']);
Route::get('/productos', [ProductoController::class, 'listarProductos']);
Route::post('/pedidos/completo', [PedidoController::class, 'insertarCompleto']);
Route::get('/pedidos', [PedidoController::class, 'listarPedidos']);
Route::put('/pedidos/{pedidoID}', [PedidoController::class, 'editarDetalles']);
Route::delete('/pedidos/{id}', [PedidoController::class, 'anularPedido']);
Route::put('/pedidos/estado/{id}', [PedidoController::class, 'cambiarEstado']);
Route::get('/pedidos/filtrar', [PedidoController::class, 'filtrarPedidos']);
Route::get('/dashboard/estadisticas', [DashboardController::class, 'dashboard']);
