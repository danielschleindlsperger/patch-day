<?php

use Carbon\Carbon;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Project::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Fake Project',
    ];
});

$factory->define(App\PatchDay::class, function (Faker\Generator $faker) {
    return [
        'cost' => 200,
        'start_date' => new Carbon('now +2 weeks'),
        'active' => true,
    ];
});

$factory->define(App\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Fake Company',
    ];
});