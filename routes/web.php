<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandProductController;
use App\Http\Controllers\CategoryProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/trang-chu', [HomeController::class, 'index']);

Route::get('/admin', [AdminController::class, 'index']);
Route::get('/dashboard', [AdminController::class, 'show_dashboard']);
Route::post('/admin-dashboard', [AdminController::class, 'dashboard']);
Route::get('/logOut', [AdminController::class, 'logOut']);

//Category product
Route::get('/add-category-product', [CategoryProductController::class, 'add_category_product']);
Route::get('/edit-category-product/{category_product_id}', [CategoryProductController::class, 'edit_category_product']);
Route::get('/delete-category-product/{category_product_id}', [CategoryProductController::class, 'delete_category_product']);
Route::get('/all-category-product', [CategoryProductController::class, 'all_category_product']);

Route::get('/unactive-category-product/{category_product_id} ', [CategoryProductController::class, 'unactive_category_product']);
Route::get('/active-category-product/{category_product_id} ', [CategoryProductController::class, 'active_category_product']);

Route::post('/save-category-product', [CategoryProductController::class, 'save_category_product']);
Route::post('/update-category-product/{category_product_id}', [CategoryProductController::class, 'update_category_product']);

//Brand product
Route::get('/add-brand-product', [BrandProductController::class, 'add_brand_product']);
Route::get('/edit-brand-product/{brand_product_id}', [BrandProductController::class, 'edit_brand_product']);
Route::get('/delete-brand-product/{brand_product_id}', [BrandProductController::class, 'delete_brand_product']);
Route::get('/all-brand-product', [BrandProductController::class, 'all_brand_product']);

Route::get('/unactive-brand-product/{brand_product_id} ', [BrandProductController::class, 'unactive_brand_product']);
Route::get('/active-brand-product/{brand_product_id} ', [BrandProductController::class, 'active_brand_product']);

Route::post('/save-brand-product', [BrandProductController::class, 'save_brand_product']);
Route::post('/update-brand-product/{brand_product_id}', [BrandProductController::class, 'update_brand_product']);
