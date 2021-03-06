<?php

Auth::routes();

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
    Route::delete('projects/{project}/delete-technology', 'ProjectController@deleteTech');
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
        'only' => ['index', 'show', 'update']
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
    Route::delete('projects/{project}/cancel', 'SignupController@cancel');
    Route::get('projects/{project}/registered-patch-days', 'SignupController@registeredPatchDays');
    Route::get('projects/{project}/signup', 'SignupController@possibleSignups');
});
