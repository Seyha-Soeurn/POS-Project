<?php

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

    // Get data to view
    Route::get('products', 'ProductCrudController@listProduct');
    Route::post('products/filter', 'ProductCrudController@filterProduct');
    Route::get('products/{id}', 'ProductCrudController@getProduct');
    Route::get('charts/weekly-purchases', 'Charts\WeeklyPurchasesChartController@response')->name('charts.weekly-purchases.index');
    Route::get('charts/weekly-sells', 'Charts\WeeklySellsChartController@response')->name('charts.weekly-sells.index');

    // Import
    Route::post('import', 'ImportController@import');
}); // this should be the absolute last line of this file