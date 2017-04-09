<?php

// Company resource
Route::resource('companies', 'CompanyController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
]);
Route::get('companies/{companyId}/projects', 'CompanyController@showCompanysProjects');

// Project resource
Route::resource('projects', 'ProjectsController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
]);

// PatchDay resource
Route::resource('patch-days', 'PatchDayController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
]);


