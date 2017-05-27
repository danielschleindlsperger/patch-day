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
        'base_price' => random_int(10000, 80000),
        'penalty' => random_int(50000, 500000),
    ];
});

$factory->define(App\PatchDay::class, function (Faker\Generator $faker) {
    $randomStart = Carbon::now()->toDateTimeString();
    $randomEnd = Carbon::now()->addMonths(12)->toDateTimeString();
    return [
        'date' => $faker->dateTimeBetween($randomStart, $randomEnd),
    ];
});

$factory->define(App\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->define(App\Protocol::class, function (Faker\Generator $faker) {
    $done = (bool)rand(0, 1);
    return [
        'comment' => $done ? $faker->sentence(10) : null,
        'done' => $done,
    ];
});

$factory->define(App\Technology::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'version' => random_int(0, 9) . '.' . random_int(0, 9) . '.' . random_int
            (0, 9),
    ];
});