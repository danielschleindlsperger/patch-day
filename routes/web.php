<?php

// serve app entry point
Route::get('/', function () {
    return view('index');
});

//Auth::routes();

Route::post('/login', ['as' => 'login', 'uses' => 'Auth\AuthController@authenticate']);
Route::post('/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);

Route::group(['middleware' => ['web', 'auth']], function () {

    // Company resource
    Route::resource('companies', 'CompanyController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // Project resource
    Route::post('projects/{project}/patch-days', 'ProjectController@projectSignup');
    Route::resource('projects', 'ProjectController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // PatchDay resource
    Route::get('patch-days/upcoming', 'PatchDayController@upcoming');
    Route::resource('patch-days', 'PatchDayController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // Protocol resource
    Route::resource('protocols', 'ProtocolController', [
        'only' => ['index', 'show', 'update', 'destroy']
    ]);

    // User resource
    Route::get('users/me', 'UserController@showMe');
    Route::resource('users', 'UserController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // Technology resource
    Route::get('technologies/{name}', 'TechnologyController@showVersionsForTech');
    Route::resource('technologies', 'TechnologyController', [
        'only' => ['index', 'store', 'update', 'destroy']
    ]);
});