<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\VerificationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/getUnreadMessageCount', [AppController::class, 'unreadCount']);
});

Route::prefix('admin')->middleware('auth:api-admin')->group(function () {
    Route::get('/user', function () {
        return auth()->user();
    });

    Route::get('/verifications', [VerificationsController::class, 'allVerificationsApi'])->name('api.verifications.index');
    Route::get('/users/{user}/verifications', [VerificationsController::class, 'getByStudentApi'])->name('api.verifications.byStudent');
});
