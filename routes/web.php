<?php

use App\Http\Controllers\AdminAppController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



// Auth::routes();
Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.action');

Route::middleware('auth')->group(function () {
    Route::get('/', [AppController::class, 'index'])->name('user.index');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'viewLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.doLogin');

    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminAppController::class, 'index'])->name('admin.index');
        Route::get('/profile', [AdminAppController::class, 'profile'])->name('admin.profile');


        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
