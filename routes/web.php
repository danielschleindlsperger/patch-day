<?php

Route::get('/projects', 'ProjectsController@index');
Route::post('/projects/create', 'ProjectsController@store');
