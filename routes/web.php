<?php

// serve app entry point
Route::get('/', function () {
    return view('index');
});

//Auth::routes();

Route::post('/login', ['as' => 'login', 'uses' => 'Auth\AuthController@authenticate']);

Route::group(['middleware' => ['web', 'auth']], function () {

    // Company resource
    Route::resource('companies', 'CompanyController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // Project resource
    Route::resource('projects', 'ProjectController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // PatchDay resource
    Route::resource('patch-days', 'PatchDayController', [
        'only' => ['index', 'show','destroy']
    ]);

    // Protocol resource
    Route::get('protocols/upcoming', 'ProtocolController@showUpcoming');
    Route::resource('protocols', 'ProtocolController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // User resource
    Route::get('users/me', 'UserController@showMe');
    Route::resource('users', 'UserController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // Technology resource
    Route::resource('technologies', 'TechnologyController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);
});