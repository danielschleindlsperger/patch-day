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
    Route::post('projects/{project}/patch-days', 'ProjectController@projectSignup');
    Route::delete('projects/{project}/patch-days', 'ProjectController@cancelSignup');
    Route::resource('projects', 'ProjectController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // PatchDay resource
    Route::resource('patch-days', 'PatchDayController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
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
    Route::get('technologies/{name}', 'TechnologyController@showVersionsForTech');
    Route::resource('technologies', 'TechnologyController', [
        'only' => ['index', 'store', 'update', 'destroy']
    ]);
});