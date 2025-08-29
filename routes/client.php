<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientUsageController;

// 認証画面
Route::get('/client/login', [ClientAuthController::class, 'showLoginForm'])->name('client.login');
Route::post('/client/login', [ClientAuthController::class, 'login']);
Route::post('/client/logout', [ClientAuthController::class, 'logout'])->name('client.logout');

// 認証後の画面（auth:client）
Route::middleware('auth:client')->group(function () {
    Route::get('/client/dashboard', [ClientUsageController::class, 'index'])->name('client.index');
    Route::get('/client/receipt/{usage}', [ClientUsageController::class, 'receipt'])->name('client.receipt');
});
