<?php

use App\Http\Livewire\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Livewire\CodigoBarrasGenerator;
use App\Http\Controllers\ProductController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/generar-codigo-barras', CodigoBarrasGenerator::class)->name('generar-codigo-barras');
    Route::get('/dashboard',)->name('generar-codigo-barras');
    Route::get('/dashboard', [CodigoBarrasGenerator::class,'getCodigosGenerados'], function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::middleware('can:create product')->group(function () {
    // Ruta para crear un producto
        Route::resource('/products', App\Http\Controllers\ProductController::class);
        
    });
    Route::middleware('can:create user')->group(function () {
        Route::resource('/users', App\Http\Controllers\UserController::class);
        Route::name('users.create')->get('/users/create', [UserController::class, 'create']);
    });
});

