<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StoreHoursController;
use App\Http\Controllers\HomeController;
use App\Models\StoreHour;

Route::get('/settings/store-hours', function () {
    $storeHours = StoreHour::first();
    return Inertia::render('settings/StoreHours', [
        'storeHours' => $storeHours,
    ]);
})->name('settings.store-hours');

Route::post('/settings/store-hours', [StoreHoursController::class, 'update'])
    ->name('settings.store-hours.update');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('dashboard', function () {
    $storeHours = StoreHour::first();
    return Inertia::render('Dashboard', [
        'storeHours' => $storeHours,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
