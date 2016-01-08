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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Models\Customer::class, function (Faker\Generator $faker) {
    return [
        'address' => $faker->address, 
        'landline' => $faker->regexify('(0[1-8][1-8])[1-9]{3}\-[0-9]{4}'), 
        'mobile' => $faker->regexify('09[0-9]{9}'),
        'farm_address' => $faker->address, 
        'farm_type' => $faker->word, 
        'farm_landline' => $faker->regexify('\(0[1-8][1-8]\)[1-9]{3}\-[0-9]{4}'),
        'farm_mobile' => $faker->regexify('09[0-9]{9}'),
    ];
});