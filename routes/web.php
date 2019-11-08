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

    Route::group(['prefix' => 'config', 'as' => 'config.'], function () {
        Route::get('/', 'configController@index')->name('index');
        Route::post('/', 'configController@update')->name('update');
    });

    Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
        Route::get('/basic', 'reportController@fullReportBasic')->name('basic');
    });


