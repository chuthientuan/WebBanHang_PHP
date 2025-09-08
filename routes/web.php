<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/trang-chu', [HomeController::class, 'index']);

Route::get('/admin', [AdminController::class, 'index']);
Route::get('/dashboard', [AdminController::class, 'show_dashboard']);
Route::post('/admin-dashboard', [AdminController::class, 'dashboard']);
Route::get('/logOut', [AdminController::class, 'logOut']);

// category product
Route::get('/add-category-product', [CategoryProductController::class, 'add_category_product']);
Route::get('/all-category-product', [CategoryProductController::class, 'all_category_product']);

Route::get('/unactive-category-product/{category_product_id} ', [CategoryProductController::class, 'unactive_category_product']);
Route::get('/active-category-product/{category_product_id} ', [CategoryProductController::class, 'active_category_product']);

Route::post('/save-category-product', [CategoryProductController::class, 'save_category_product']);