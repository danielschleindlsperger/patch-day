<?php

Route::resource('project', 'ProjectsController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
]);

Route::resource('patch-day', 'PatchDayController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
]);

Route::resource('company', 'CompanyController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
]);
