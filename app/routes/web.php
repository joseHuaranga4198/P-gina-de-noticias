<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/buscar', [PublicController::class, 'search'])->name('search');
Route::get('/categoria/{slug}', [PublicController::class, 'category'])->name('category.show');
Route::get('/noticia/{slug}', [PublicController::class, 'show'])->name('news.show');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // News management
    Route::resource('noticias', AdminNewsController::class)->parameters(['noticias' => 'news'])->names([
        'index' => 'news.index',
        'create' => 'news.create',
        'store' => 'news.store',
        'edit' => 'news.edit',
        'update' => 'news.update',
        'destroy' => 'news.destroy',
    ]);
    Route::patch('/noticias/{news}/toggle-status', [AdminNewsController::class, 'toggleStatus'])->name('news.toggle-status');

    // Category management
    Route::resource('categorias', AdminCategoryController::class)->parameters(['categorias' => 'category'])->names([
        'index' => 'categories.index',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);
});
