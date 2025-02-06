<?php

use App\Http\Controllers\CafeController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin');
    })->name('admin.login');

    Route::get('/register', function () {
        return view('admin');
    })->name('admin.register');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/{any?}', function () {
            return view('admin');
        })->where('any', '.*');
    });
});

Route::middleware('web')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('homepage');
    Route::get('/cafe/{cafe}', [CafeController::class, 'show'])->name('cafe.show');
    Route::get('/cafe/{cafe}/category/{category}', [CafeController::class, 'menu'])->name('cafe.menu');
});
