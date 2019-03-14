<?php

Route::group(['middleware' => 'web', 'domain' => 'admin.test.com', 'namespace' => 'Modules\Admin\Http\Controllers', 'as' => 'admin.'], function () {
    Route::get('/login', 'AuthController@loginPage');
    Route::post('/login', 'AuthController@login');
    Route::get('/logout', 'AuthController@logout');
    Route::group(['prefix' => 'common'], function () {
        Route::get('/captcha', 'CommonController@captcha');
    });

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'AdminController@homePage']);
        Route::get('/admin_resources/all', ['as' => 'getAccessResources', 'uses' => 'AdminResourceController@getAll']);
    });
});
