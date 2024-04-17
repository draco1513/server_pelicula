<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\AuthController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout'); 
    Route::post('register', 'register');
    Route::post('refresh', 'refresh');
    Route::post('me', 'me');
});

Route::controller(ServicioController::class)->group(function () {
    Route::get('servicios', 'getServicios');
    Route::post('servicios/saveServicio', 'saveServicio');
    Route::delete('/deleteServicio', 'deleteServicio');
});

Route::controller(ClienteController::class)->group(function () {
    Route::get('clientes', 'getClientes');
    Route::get('clientes-cuenta', 'obtenerClientesConCuentas');
    Route::get('clientes-cuenta/info', 'obtenerInformacionCuentasCliente');
    Route::post('clientes/saveCliente', 'saveCliente');
    Route::delete('/deleteCliente', 'deleteCliente');
    
});

Route::controller(CuentaController::class)->group(function () {
    Route::get('cuentas', 'getCuentas');
    Route::get('cuentasAll', 'getCuentasAll');
    Route::post('cuentas/saveCuenta', 'saveCuenta');
    Route::delete('/deleteCuenta', 'deleteCuenta');
});

Route::controller(PerfilController::class)->group(function () {
    Route::get('perfiles', 'getPerfiles');
    Route::get('perfiles/clientes', 'getClientesAll');
    Route::post('perfiles/savePerfilesCuenta', 'savePerfilesCuenta');
    //Route::delete('/deleteServicio', 'deleteServicio');
});

Route::controller(MovimientoController::class)->group(function () {
    Route::get('movimientos', 'getMovimientos');
    Route::get('movimientos-pagados', 'getMovimientosPagados');
    Route::post('movimientos/saveMovimiento', 'saveMovimiento');
    Route::delete('/deleteMovimiento', 'deleteMovimiento');
});
