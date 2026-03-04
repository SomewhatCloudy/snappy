<?php

use App\Http\Controllers\Admin\StoreManagementController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Support\Facades\Route;

//
Route::inertia('/', 'StoreFinder')->name('home');
Route::inertia('dashboard', 'StoreFinder')->name('dashboard');

// API
Route::get('/stores/nearby', [StoreController::class, 'nearbyCoords']);
Route::get('/stores/can-deliver/{store}', [StoreController::class, 'canDeliver']);

Route::get('/search/locations', [StoreController::class, 'searchLocations']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {

        // Pages
        Route::get('/stores', [StoreManagementController::class, 'index'])->name('stores.index');
        Route::get('/stores/create', [StoreManagementController::class, 'create'])->name('stores.create');
        Route::get('/stores/{store}/edit', [StoreManagementController::class, 'edit'])->name('stores.edit');

        // API
        Route::put('/stores/{store}', [StoreManagementController::class, 'update'])->name('stores.update');
        Route::post('/stores', [StoreManagementController::class, 'store'])->name('stores.store');
        Route::delete('/stores/{store}', [StoreManagementController::class, 'destroy'])->name('stores.destroy');
    });
});

require __DIR__.'/settings.php';
