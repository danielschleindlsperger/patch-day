<?php

Auth::routes();

//Route::get('/login', ['uses' => 'Auth\AuthController@login']);
//Route::post('/login', ['uses' => 'Auth\AuthController@authenticate']);
//Route::post('/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);

Route::group(['middleware' => ['web', 'auth']], function () {

    // serve app entry point
    Route::get('/', function () {
        return view('index');
    });

    // Company resource
    Route::resource('companies', 'CompanyController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // Project resource
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

    // PatchDay Signups
    Route::post('projects/{project}/signup', 'SignupController@signup');
});
