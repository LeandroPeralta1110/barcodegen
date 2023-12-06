<?php

use Spatie\Permission\Middlewares\RoleMiddleware as RoleMiddleware;
use App\Http\Livewire\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CodigoBarrasGenerator;
use App\Http\Controllers\ProductController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\UserController;
use App\Http\Livewire\Dashboard;

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

// ...

// Rutas protegidas por autenticación y verificación de correo
Route::middleware(['auth:sanctum', 'verified', 'CheckInactiveUser'])->group(function () {
        // Rutas que requieren verificación de usuario activo
        Route::get('/generar-codigo-barras', CodigoBarrasGenerator::class)->name('generar-codigo-barras');
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        // Rutas para productos (requieren el permiso 'create product')
        Route::middleware(['can:create product'])->group(function () {
            Route::resource('/products', App\Http\Controllers\ProductController::class);
        });

        // Rutas para usuarios (requieren el permiso 'create user')
        Route::middleware(['can:create user'])->group(function () {
            Route::resource('/users', App\Http\Controllers\UserController::class);
            Route::name('users.create')->get('/users/create', [UserController::class, 'create']);
        });

        Route::middleware(['can:create product_area_lavazza'])->group(function () {
            Route::resource('/products/lavazza', App\Http\Controllers\Lavazza\ProductController::class);
            Route::name('products.lavazza.create')->get('/products/lavazza/create', [ProductController::class, 'create']);
        });
    
        Route::middleware(['can:create user_area_lavazza'])->group(function () {
            Route::resource('/users/lavazza', App\Http\Controllers\UserController::class);
            Route::name('users.lavazza.create')->get('/users/lavazza/create', [UserController::class, 'create']);
        });
});


