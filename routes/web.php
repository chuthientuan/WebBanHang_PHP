<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandProductController;
use App\Http\Controllers\CategoryProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

//forntend
Route::get('/', [HomeController::class, 'index']);
Route::get('/trang-chu', [HomeController::class, 'index']);
Route::post('/tim-kiem', [HomeController::class, 'search']);

//danh muc san pham trang chu
Route::get('/danh-muc-san-pham/{category_id}', [CategoryProductController::class, 'show_category_home']);
Route::get('/thuong-hieu-san-pham/{brand_id}', [BrandProductController::class, 'show_brand_home']);
Route::get('/chi-tiet-san-pham/{product_id}', [ProductController::class, 'details_product']);

//backend
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

//Product
Route::get('/add-product', [ProductController::class, 'add_product']);
Route::get('/edit-product/{product_id}', [ProductController::class, 'edit_product']);
Route::get('/delete-product/{product_id}', [ProductController::class, 'delete_product']);
Route::get('/all-product', [ProductController::class, 'all_product']);

Route::get('/unactive-product/{product_id} ', [ProductController::class, 'unactive_product']);
Route::get('/active-product/{product_id}', [ProductController::class, 'active_product']);

Route::post('/save-product', [ProductController::class, 'save_product']);
Route::post('/update-product/{product_id}', [ProductController::class, 'update_product']);

//Cart
Route::post('/save-cart', [CartController::class, 'save_cart']);
Route::post('/update-cart-quantity', [CartController::class, 'update_cart_quantity']);
Route::get('/show-cart', [CartController::class, 'show_cart']);
Route::get('/delete-to-cart/{rowId}', [CartController::class, 'delete_to_cart']);
Route::post('/add-cart-ajax', [CartController::class, 'add_cart_ajax']);

//Checkout

Route::get('/login-checkout', [CheckoutController::class, 'login_checkout']);
Route::get('/logout-checkout', [CheckoutController::class, 'logout_checkout']);
Route::post('/add-customer', [CheckoutController::class, 'add_customer']);
Route::post('/order-place', [CheckoutController::class, 'order_place']);
Route::post('/login-customer', [CheckoutController::class, 'login_customer']);
Route::get('/checkout', [CheckoutController::class, 'checkout']);
Route::get('/payment', [CheckoutController::class, 'payment']);
Route::post('/save-checkout-customer', [CheckoutController::class, 'save_checkout_customer']);

//Order
Route::get('/manage-order', [OrderController::class, 'manage_order']);
Route::get('/view-order/{orderId}', [OrderController::class, 'view_order']);