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
    return view('skeleton.scafolding');
});

Route::get('skeleton', function () {
    return view('home');
});

Route::get('home', function () {
    return view('welcome');
});

Route::resource('ajax-crud-list','UsersController');

Route::get('user/json','UsersController@json');

Route::post('ajax-crud-list/store','UsersController@store');

Route::get('ajax-crud-list/delete/{id?}','UsersController@destroy');

Route::get('ajax-crud-list/deleteall','UsersController@deleteall');

Route::resource('category','CategoryController');

Route::get('category_json','CategoryController@json');

Route::post('category_store','CategoryController@store');

Route::get('category_destroy/{id?}','CategoryController@destroy');

Route::resource('article','ArticleController');