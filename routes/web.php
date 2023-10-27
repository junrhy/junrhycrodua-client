<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\Auth;

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

Route::get('/', function () {
    return view('login');
});

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

Route::middleware([App\Http\Middleware\Auth::class])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::resource('sales', App\Http\Controllers\SaleController::class);
    Route::resource('inventories', App\Http\Controllers\InventoryController::class);
});
