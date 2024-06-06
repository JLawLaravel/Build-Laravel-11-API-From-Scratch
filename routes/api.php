<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/lists/categories', [CategoryController::class, 'list'])->name('categories.list');

// Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
// Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
// Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
// Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
// Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::apiResource('categories', CategoryController::class);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');