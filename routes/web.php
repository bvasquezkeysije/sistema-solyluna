<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('/ventas', 'admin.modules.ventas')->name('ventas');
    Route::view('/productos', 'admin.modules.productos')->name('productos');
    Route::view('/habitaciones', 'admin.modules.habitaciones')->name('habitaciones');
    Route::view('/clientes', 'admin.modules.clientes')->name('clientes');
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::view('/configuracion', 'admin.modules.configuracion')->name('configuracion');
});

Route::redirect('/dashboard', '/admin/dashboard')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
