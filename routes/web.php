<?php

use App\Http\Controllers\dashboardController;
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

Route::get('/', [dashboardController::class, 'welcome']);

// ...

// Rutas protegidas por autenticación y verificación de correo
Route::middleware(['auth:sanctum', 'verified', 'CheckInactiveUser'])->group(function () {
    Route::get('/generar-codigo-barras', CodigoBarrasGenerator::class)->name('generar-codigo-barras');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Rutas para productos y usuarios accesibles por todos los roles (incluido administrador y administrador_lavazza)
    Route::middleware('can:create user')->group(function () {
        Route::resource('/users', UserController::class);
        Route::name('users.create')->get('/users/create', [UserController::class, 'create']);

        Route::resource('/products', ProductController::class);
        Route::name('products.create')->get('/products/create', [ProductController::class, 'create']);
    });    
});