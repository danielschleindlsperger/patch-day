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
    Route::get('companies/{companyId}/projects', 'CompanyController@showCompanysProjects');

    // Project resource
    Route::resource('projects', 'ProjectController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);
    Route::get('projects/{projectId}/protocols', 'ProjectController@showProjectsProtocols');

    // PatchDay resource
    Route::resource('patch-days', 'PatchDayController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // Protocol resource
    Route::resource('protocols', 'ProtocolController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // User resource
    Route::resource('users', 'UserController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);
});