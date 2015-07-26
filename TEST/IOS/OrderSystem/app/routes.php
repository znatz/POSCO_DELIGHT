<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function () {
    return View::make('auth.login');
});

Route::post('/iosReceiver', "iosReceiver@index");
Route::post('/iosReceiver2', "iosReceiver@index");

Route::group(['before' => 'sentry'], function () {
    Route::resource('dataFromIOs', 'DataFromIOsController');
    Route::resource('employees', 'EmployeesController');
    Route::resource('items', 'ItemsController');
    Route::post('dataFromIDs/clear', ['as' => 'dataFromIOs.clear', 'uses' => 'DataFromIOsController@clear']);
    Route::get('item/{id}', ['as' => 'item.show.img', 'uses' => 'ItemsController@showImg']);
    Route::post('item/{id}', ['as' => 'item.retrieve', 'uses' => 'ItemsController@getItem']);
    Route::resource('categories', 'CategoriesController');
    Route::resource('shops', 'ShopsController');
    Route::resource('settings', 'SettingsController');
    Route::resource('shopsettings', 'ShopsettingsController');
    Route::resource('receiptsettings', 'ReceiptsettingsController');

    Route::get('/user/signup', array('as' => 'signup', 'uses' => 'AuthController@showSignUp'));
    Route::post('/user/signup', 'AuthController@execSignUp');
    Route::get('/user/logout', array('as' => 'logout', 'uses' => 'AuthController@execLogout'));
});


Route::get('/user/login', array('as' => 'login', 'uses' => 'AuthController@showLogin'));
Route::post('/user/login', 'AuthController@execLogin');
