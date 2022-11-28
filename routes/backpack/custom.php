<?php

use App\Http\Controllers\Admin\ProductCrudController;
use App\Http\Controllers\Admin\PurchaseCrudController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::crud('customer', 'CustomerCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('supplier', 'SupplierCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('order', 'OrderCrudController');
    Route::crud('purchase', 'PurchaseCrudController');
    Route::crud('order-product', 'OrderProductCrudController');

    // product route extra
    Route::get('getproducts',[ProductCrudController::class,'getProducts']);
    // purchase route extra
    Route::get('getpurchase',[PurchaseCrudController::class,'getPurchases']);
}); // this should be the absolute last line of this file