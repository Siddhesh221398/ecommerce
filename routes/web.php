<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
    Route::group(['middleware' => 'auth.admin'], function () {
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::resource('category', \App\Http\Controllers\CategoryController::class);
        Route::resource('brand', \App\Http\Controllers\BrandController::class);
        Route::resource('product', \App\Http\Controllers\ProductController::class);
        Route::post('/product/status/{product}', [App\Http\Controllers\ProductController::class, 'status'])->name('product.status');
        Route::delete('/product/document/{document}', [App\Http\Controllers\ProductController::class, 'documentDestroy'])->name('product.documentDestroy');
        Route::get('orders',[App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('order/show/{order}',[App\Http\Controllers\OrderController::class, 'show'])->name('order.show');
    });
});

Route::get('/', [\App\Http\Controllers\User\HomeController::class, 'homePage'])->name('homePage');
Route::group(['prefix' => 'user'], function () {
    Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'userRegister'])->name('user.register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('user.signUp');
    Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'userLogin'])->name('user.login');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('user.signIn');

    Route::get('/products', [\App\Http\Controllers\User\ProductController::class, 'products'])->name('products');
    Route::get('/product/{product}', [\App\Http\Controllers\User\ProductController::class, 'singleProduct'])->name('singleProduct');
    Route::get('/categories', [\App\Http\Controllers\User\CategoryController::class, 'categories'])->name('categories');
    Route::get('/brands', [\App\Http\Controllers\User\BrandController::class, 'brands'])->name('brands');
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/carts', [\App\Http\Controllers\User\AddCartController::class, 'carts'])->name('carts');
        Route::post('/addToCart', [\App\Http\Controllers\User\AddCartController::class, 'addToCart'])->name('addToCart');
        Route::post('/removeCart', [\App\Http\Controllers\User\AddCartController::class, 'removeCart'])->name('removeCart');
        Route::post('/decreaseQty', [\App\Http\Controllers\User\AddCartController::class, 'decreaseQty'])->name('decreaseQty');
        Route::post('/increaseQty', [\App\Http\Controllers\User\AddCartController::class, 'increaseQty'])->name('increaseQty');
        Route::get('/checkout', [\App\Http\Controllers\User\AddCartController::class, 'checkout'])->name('checkout');
        Route::post('/order', [\App\Http\Controllers\User\AddCartController::class, 'order'])->name('order');
    });
});
