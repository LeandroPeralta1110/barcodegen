<?php

use App\Http\Livewire\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Livewire\CodigoBarrasGenerator;

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
Route::get('/create-user', [AdminController::class, 'showCreateUserForm'])->name('create-user');
Route::post('/create-user', [AdminController::class, 'createUser']);

// Ruta para crear un producto
Route::get('/create-product', [AdminController::class, 'showCreateProductForm'])->name('create-product');
Route::post('/create-product', [AdminController::class, 'crearProducto']);