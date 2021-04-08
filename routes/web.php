<?php
/* Copyright © 2020 Chien-Yu Lin. All rights reserved.*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CartController;

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


//Auth::routes();
Auth::routes(['verify' => true]);
//Route::get('/home', 'HomeController@index')->middleware('verified');
Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::get('/about', 'App\Http\Controllers\RulesController@index');
Route::get('/type0', 'App\Http\Controllers\PostsController@type0');
Route::get('/type1', 'App\Http\Controllers\PostsController@type1');
Route::get('/type2', 'App\Http\Controllers\PostsController@type2');
Route::get('/search', 'App\Http\Controllers\PostsController@index');
Route::get('/choose', 'App\Http\Controllers\SendController@martmap');

Route::post('/search', 'App\Http\Controllers\PostsController@search');

Route::resource('posts', 'App\Http\Controllers\PostsController');
Route::resource('borrows', 'App\Http\Controllers\SendController');
Route::resource('carousel', 'App\Http\Controllers\CarouselController');
Route::resource('order', 'App\Http\Controllers\OrderController');

Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->middleware('verified','active','admin');

Auth::routes();

Route::get('/home', 'App\Http\Controllers\PostsController@index')->name('home');

/*Route::get('/profile/{user}', 'App\Http\Controllers\ProfilesController@index')->name('profile.show');
Route::get('/profile/{user}/edit', 'App\Http\Controllers\ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}', 'App\Http\Controllers\ProfilesController@update')->name('profile.update');*/

Route::get('/cart','App\Http\Controllers\CartController@index')->name('cart.index')->middleware('verified','active');
Route::post('/cart','App\Http\Controllers\CartController@add')->name('cart.add')->middleware('verified','active');
Route::post('/cart/conditions','App\Http\Controllers\CartController@addCondition')->name('cart.addCondition')->middleware('verified','active');
Route::delete('/cart/conditions','App\Http\Controllers\CartController@clearCartConditions')->name('cart.clearCartConditions')->middleware('verified','active');
Route::get('/cart/details','App\Http\Controllers\CartController@details')->name('cart.details')->middleware('verified','active');
Route::delete('/cart/{id}','App\Http\Controllers\CartController@delete')->name('cart.delete')->middleware('verified','active');

Route::post('/borrows','App\Http\Controllers\SendController@store')->middleware('verified','active');

Route::post('/send/lookup','App\Http\Controllers\SendController@lookup')->middleware('verified','active');
Route::post('/send/lookup','App\Http\Controllers\SendController@lookup')->middleware('verified','active');
Route::get('/send','App\Http\Controllers\OrderController@index')->middleware('verified','active');

Route::post('/paymentCheck','App\Http\Controllers\SendController@paymentCheck');
Route::get('/payed','App\Http\Controllers\SendController@redirectFromECpay');

//Route::get('/order','App\Http\Controllers\OrderController@index')->middleware('verified');
Route::post('/order/{id}','App\Http\Controllers\OrderController@verify')->middleware('verified','active','admin');
Route::name('/order')->get('/order', 'App\Http\Controllers\OrderController@index')->middleware('verified','active','admin');
Route::name('/myorder')->get('/myorder', 'App\Http\Controllers\OrderController@myorder')->middleware('verified','active');

Route::get('/account','App\Http\Controllers\AccountController@index')->middleware('verified','active','admin');
Route::post('/account/edit','App\Http\Controllers\AccountController@edit')->middleware('verified','active','admin');

Route::get('/bulletin','App\Http\Controllers\BulletinController@index')->middleware('verified','active','admin');
Route::post('/bulletin/p','App\Http\Controllers\BulletinController@edit')->middleware('verified','active','admin');

Route::get('/carousel','App\Http\Controllers\CarouselController@index')->middleware('verified','active','admin');
Route::post('/carousel/p','App\Http\Controllers\CarouselController@edit')->middleware('verified','active','admin');
Route::post('/carousel/{id}','App\Http\Controllers\CarouselController@update')->middleware('verified','active','admin');
Route::delete('/carousel/d/{id}','App\Http\Controllers\CarouselController@destroy')->middleware('verified','active','admin');

Route::get('/rules','App\Http\Controllers\RulesController@page')->middleware('verified','active','admin');
Route::post('/rules/p','App\Http\Controllers\RulesController@edit')->middleware('verified','active','admin');



