<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

    
Route::get('/mov', 'MovController@index')->name('mov.index');
//Route::get('/product', 'ProductController@index')->name('product.index');
Route::get('/product/view', 'ProductController@view')->name('product.view');

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', 'InventoryController@index')->name('inventory.index');
    Route::post('/inventory/move', 'InventoryController@store')->name('inventory.store');

    // Configuracion
    Route::get('/config', 'ConfigController@index')->name('config.index');
    Route::get('/product', 'ProductController@index')->name('product.index');
    Route::get('/product/update', 'ProductController@update')->name('product.update');

    Route::post('/product/store', 'ProductController@store')->name('product.store');
    Route::post('/product/storeupdate', 'ProductController@storeupdate')->name('product.storeupdate');
    Route::get('/product/storedelete', 'ProductController@storedelete')->name('product.storedelete');
});
