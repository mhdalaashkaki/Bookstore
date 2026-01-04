<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LanguageController;

// تبديل اللغة
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// صفحة اختبار اللغات
Route::get('/language-test', function () {
    return view('language-test');
})->name('language.test');

// الصفحة الرئيسية للمتجر
Route::get('/', function () {
    return view('welcome');
})->name('home');

/**
 * مسارات المصادقة
 */
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

// تسجيل الخروج
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/**
 * مسارات المتجر العام
 * يمكن الوصول إليها من غير تسجيل الدخول
 */
Route::middleware('web')->group(function () {
    // عرض المنتجات
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/api/products/{product}', [ProductController::class, 'getProduct'])->name('api.products.show');
});

/**
 * مسارات المستخدم المسجل
 * يجب تسجيل الدخول للوصول إليها
 */
Route::middleware(['auth'])->group(function () {
    // الطلبات الخاصة بالمستخدم
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::delete('/orders/{order}', [OrderController::class, 'delete'])->name('orders.delete');
    Route::get('/my-orders', [OrderController::class, 'userOrders'])->name('orders.user');
});

/**
 * مسارات لوحة الإدارة
 * يجب أن يكون المستخدم أدمن للوصول
 */
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // إدارة المنتجات
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::post('/products/{product}/reject', [AdminController::class, 'rejectProduct'])->name('products.reject');
    Route::post('/products/{product}/restore', [AdminController::class, 'restoreProduct'])->name('products.restore');

    // إدارة الطلبات
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/completed', [AdminController::class, 'completedOrders'])->name('orders.completed');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::delete('/orders/{order}', [AdminController::class, 'deleteOrder'])->name('orders.delete');
    Route::delete('/orders/{order}/force', [AdminController::class, 'forceDeleteOrder'])->name('orders.forceDelete');

    // إدارة المستخدمين
    Route::get('/users', [AdminController::class, 'users'])->name('users');
});
