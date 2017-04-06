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
        'academic_title_id'=>$faker->numberBetween(1,10),
        'photo'=>$faker->text(12),
        'log_id'=> $faker->numberBetween(1000,9999)
    ];
});

$factory->define(App\Models\AcademicTitle::class, function (Faker\Generator $faker) {
    return [
        'academic_title'=>$faker->jobTitle
    ];
});

$factory->define(App\Models\Subjecttype::class, function (Faker\Generator $faker) {
    return [
        'subjecttype'=>$faker->firstNameMale
    ];
});


$factory->define(App\Models\Subject::class, function (Faker\Generator $faker) {
    return [
        'subject' => $faker->text(12),
        'ects' => $faker->numberBetween(1,12),
        'semester' => $faker->numberBetween(1,2),
        'subjecttype_id' => $faker->numberBetween(1,2)
    ];
});