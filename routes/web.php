<?php

// serve app entry point
Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::group(['middleware' => ['web', 'auth:api']], function () {
    // Company resource
    Route::resource('companies', 'CompanyController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);
    Route::get('companies/{companyId}/projects', 'CompanyController@showCompanysProjects');

    // Project resource
    Route::resource('projects', 'ProjectController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);
    Route::get('projects/{projectId}/patch-days', 'ProjectController@showProjectsPatchDays');

    // PatchDay resource
    Route::resource('patch-days', 'PatchDayController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);
    Route::get('patch-days/{patchDayId}/protocols', 'PatchDayController@showPatchDaysProtocols');

    // Protocol resource
    Route::resource('protocols', 'ProtocolController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);

    // User resource
    Route::resource('users', 'UserController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy']
    ]);
});