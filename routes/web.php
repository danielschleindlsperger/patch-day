<?php

Route::resource('/project', 'ProjectsController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
]);
