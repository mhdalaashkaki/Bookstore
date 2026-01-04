<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * مسارات المصادقة
 */

// مسارات غير المسجلين (guest routes)
Route::middleware('guest')->group(function () {
    // صفحة تسجيل الدخول
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // معالجة تسجيل الدخول
    Route::post('/login', [AuthController::class, 'login']);

    // صفحة التسجيل
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    // معالجة التسجيل
    Route::post('/register', [AuthController::class, 'register']);
});

// مسارات المسجلين (authenticated routes)
Route::middleware('auth')->group(function () {
    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
