<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'email'=> $faker->email,
        'password'=> bcrypt('secret'),
        'last_name'=> $faker->lastName,
        'first_name'=> $faker->firstName,
        'personal_number' => $faker->text(10),
        'cpa_id' => $faker->numberBetween(1,3),
        'acedemical_title_id'=>$faker->numberBetween(1,10),
        'photo'=>$faker->text(12),
        'log_id'=> $faker->numberBetween(1000,9999)
    ];
});

$factory->define(App\Models\AcademicalTitle::class, function (Faker\Generator $faker) {
    return [
        'academical_title'=>$faker->jobTitle
    ];
});