<?php

use App\Http\Livewire\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Livewire\CodigoBarrasGenerator;
use App\Http\Controllers\ProductController;

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
});

// Ruta para crear un usuario
Route::post('/create-user', [AdminController::class, 'createUser'])->name('create-user');

// Ruta para crear un producto
Route::resource('/products', App\Http\Controllers\ProductController::class);
