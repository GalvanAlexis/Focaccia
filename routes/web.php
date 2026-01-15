<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoriasController;
use App\Http\Controllers\Admin\PedidosController;
use App\Http\Controllers\Admin\CajaChicaController;

// ---------------- HOME (público) ----------------
Route::get('/', [HomeController::class, 'index'])->name('home');

// ---------------- CARRITO (público) ----------------
Route::get('carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::post('carrito/eliminar', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
Route::get('carrito/getCount', [CarritoController::class, 'getCount'])->name('carrito.getCount');

// ---------------- CARRITO (finalizar pedido SIN requerir login) ----------------
Route::post('carrito/finalizar', [CarritoController::class, 'finalizar'])->name('carrito.finalizar');

// ---------------- ADMIN (solo admin) ----------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // PEDIDOS
    Route::get('pedidos', [PedidosController::class, 'index'])->name('pedidos.index');
    Route::get('pedidos/ver/{id}', [PedidosController::class, 'ver'])->name('pedidos.ver');
    Route::match(['GET', 'POST'], 'pedidos/editar/{id}', [PedidosController::class, 'editar'])->name('pedidos.editar');
    Route::post('pedidos/cambiarEstado/{id}', [PedidosController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');
    Route::post('pedidos/actualizarItem', [PedidosController::class, 'actualizarItem'])->name('pedidos.actualizarItem');
    Route::post('pedidos/agregarPlato', [PedidosController::class, 'agregarPlato'])->name('pedidos.agregarPlato');
    Route::post('pedidos/eliminar/{id}', [PedidosController::class, 'eliminar'])->name('pedidos.eliminar');
    Route::get('pedidos/imprimir/{id}', [PedidosController::class, 'imprimirTicket'])->name('pedidos.imprimir');

    // CAJA CHICA
    Route::get('caja-chica', [CajaChicaController::class, 'index'])->name('caja-chica.index');
    Route::get('caja-chica/ver/{fecha}', [CajaChicaController::class, 'ver'])->name('caja-chica.ver');
    Route::post('caja-chica/agregar', [CajaChicaController::class, 'agregar'])->name('caja-chica.agregar');
    Route::match(['GET', 'POST'], 'caja-chica/editar/{id}', [CajaChicaController::class, 'editar'])->name('caja-chica.editar');
    Route::get('caja-chica/eliminar/{id}', [CajaChicaController::class, 'eliminar'])->name('caja-chica.eliminar');
    Route::get('caja-chica/archivo', [CajaChicaController::class, 'archivo'])->name('caja-chica.archivo');
    Route::get('caja-chica/imprimir/{fecha}', [CajaChicaController::class, 'imprimir'])->name('caja-chica.imprimir');
});

// ---------------- MENU y CATEGORÍAS (admin o vendedor) ----------------
Route::middleware(['auth', 'role:admin|vendedor'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD DE MENÚ
    Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('menu/crear', [MenuController::class, 'crear'])->name('menu.crear');
    Route::post('menu/guardar', [MenuController::class, 'guardar'])->name('menu.guardar');
    Route::get('menu/editar/{id}', [MenuController::class, 'editar'])->name('menu.editar');
    Route::post('menu/actualizar/{id}', [MenuController::class, 'actualizar'])->name('menu.actualizar');
    Route::get('menu/eliminar/{id}', [MenuController::class, 'eliminar'])->name('menu.eliminar');
    Route::get('menu/obtenerPlatos', [MenuController::class, 'obtenerPlatos'])->name('menu.obtenerPlatos');

    // CRUD DE CATEGORÍAS
    Route::get('categorias', [CategoriasController::class, 'index'])->name('categorias.index');
    Route::post('categorias/crear', [CategoriasController::class, 'crear'])->name('categorias.crear');
    Route::post('categorias/actualizar/{id}', [CategoriasController::class, 'actualizar'])->name('categorias.actualizar');
    Route::post('categorias/eliminar/{id}', [CategoriasController::class, 'eliminar'])->name('categorias.eliminar');
    Route::get('categorias/obtenerTodas', [CategoriasController::class, 'obtenerTodas'])->name('categorias.obtenerTodas');
});

// ---------------- PEDIDOS DE USUARIO (autenticado) ----------------
Route::middleware(['auth'])->group(function () {
    Route::get('pedido', function() {
        return view('pedido.index'); // Esta vista mostrar\u00e1 los pedidos del usuario
    })->name('pedido.index');
});

// ---------------- AUTH (Laravel UI o Breeze) ----------------
require __DIR__.'/auth.php';
