<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UsageController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/usage', [UsageController::class, 'index'])->name('usage.index');
    Route::patch('/dashboard/usage/{usage}', [UsageController::class, 'update'])->name('usage.update');
    Route::get('/dashboard/usage/export', [UsageController::class, 'export'])->name('usage.export');
});

require __DIR__.'/client.php';
