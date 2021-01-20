<?php

use App\Http\Controllers\AdminAppController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentExportController;
use App\Http\Controllers\StudentsImportController;
use App\Http\Controllers\UserModuleController;
use Illuminate\Support\Facades\Route;



// Auth::routes();
Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.action');


Route::middleware('auth')->group(function () {

    Route::middleware('user.active')->group(function () {
        Route::get('/', [AppController::class, 'index'])->name('user.index');
        Route::get('/profile', [AppController::class, 'profile'])->name('user.profile');

        Route::get('/modules', [UserModuleController::class, 'index'])->name('user.modules.index');
        Route::get('/modules/{module}', [UserModuleController::class, 'show'])->name('user.modules.show');
        Route::get('/modules/{module}/addRecord', [UserModuleController::class, 'addRecord'])->name('user.modules.addRecord');
        Route::get('/modules/{module}/editRecord/{itemGroupId?}', [UserModuleController::class, 'editRecord'])->name('user.modules.editRecord');
        Route::post('/modules/items/create', [UserModuleController::class, 'createUserItems'])->name('user.modules.createItems');
        Route::post('/modules/items/update', [UserModuleController::class, 'updateUserItems'])->name('user.modules.updateItems');
        Route::post('/modules/items/delete/{itemGroup}', [UserModuleController::class, 'deleteItemRecord'])->name('user.modules.deleteRecord');
        Route::post('/modules/items/fileupload', [UserModuleController::class, 'uploadFile'])->name('user.fileupload');
        Route::post('/modules/items/deleteUpload', [UserModuleController::class, 'deleteFile'])->name('user.deleteUpload');
    });

    Route::get('/suspended', [AuthController::class, 'suspended'])->name('user.suspended');

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

        Route::get('/students/{id}/module/{module}', [StudentController::class, 'getModuleItems'])->name('admin.students.items');
        Route::post('/items/{id}/updateStatus', [ModuleController::class, 'updateStatus'])->name('students.items.udpateStatus');
        Route::post('/items/{id}/updateInvalidMessage', [ModuleController::class, 'updateInvalidMessage'])->name('students.items.udpateInvalidMessage');
        Route::post('/itemGroups/{id}/updateStatus', [ModuleController::class, 'updateItemGroupStatus'])->name('students.itemGroups.udpateStatus');
        Route::post('/itemGroups/{id}/updateInvalidMessage', [ModuleController::class, 'updateIGInvalidMessage'])->name('students.itemGroups.udpateInvalidMessage');

        Route::resource('modules', ModuleController::class, ['except' => ['destroy']]);
        Route::post('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');

        Route::get('/module/{module}/items/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
        Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::patch('/item/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::post('/item/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

        Route::post('/items/updateOrder', [ItemController::class, 'updateOrder'])->name('items.updateOrder');
        Route::post('/items/{id}/download', [ItemController::class, 'download'])->name('items.download');

        Route::post('/students/{id}/items/update', [ItemController::class, 'updateUserItems'])->name('admin.modules.updateItems');

        Route::post('/modules/{module}/student/{id}/downloadFiles', [ModuleController::class, 'downloadZip'])->name('admin.modules.downloadFiles');

        Route::get('/export/students', [StudentExportController::class, 'index'])->name('export.students.index');
        Route::post('/export/students', [StudentExportController::class, 'export'])->name('export.students');

        Route::get('/import/students', [StudentsImportController::class, 'index'])->name('import.students.index');
        Route::post('/import/students', [StudentsImportController::class, 'import'])->name('import.students');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
