<?php

use App\Http\Controllers\AdminAppController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\StudentController;
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

        Route::resource('batches', BatchController::class, ['except' => ['destroy']]);
        Route::post('/batches/{batch}', [BatchController::class, 'destroy'])->name('batches.destroy');

        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
        Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::patch('/students/{id}', [StudentController::class, 'update'])->name('students.update');
        Route::post('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

        Route::resource('modules', ModuleController::class, ['except' => ['destroy']]);
        Route::post('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');

        Route::get('/module/{module}/items/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
        Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::patch('/item/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::post('/item/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
