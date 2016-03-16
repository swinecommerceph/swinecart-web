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
        'address_addressLine1' => $faker->word,
        'address_addressLine2' => $faker->address,
        'address_province' => $faker->word,
        'address_zipCode' => $faker->regexify('[0-9]{4}'),
        'landline' => $faker->regexify('(0[1-8][1-8])[1-9]{3}\-[0-9]{4}'),
        'mobile' => $faker->regexify('09[0-9]{9}'),
    ];
});

$factory->define(App\Models\Breeder::class, function (Faker\Generator $faker) {
    return [
        'officeAddress_addressLine1' => $faker->word,
        'officeAddress_addressLine2' => $faker->address,
        'officeAddress_province' => $faker->word,
        'officeAddress_zipCode' => $faker->regexify('[0-9]{4}'),
        'office_landline' => $faker->regexify('(0[1-8][1-8])[1-9]{3}\-[0-9]{4}'),
        'office_mobile' => $faker->regexify('09[0-9]{9}'),
        'website' => $faker->word.'.com',
        'produce' => $faker->word,
        'contactPerson_name' => $faker->name,
        'contactPerson_mobile' => $faker->regexify('09[0-9]{9}'),
    ];
});

$factory->define(App\Models\FarmAddress::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'addressLine1' => $faker->word,
        'addressLine2' => $faker->address,
        'province' => $faker->word,
        'zipCode' => $faker->regexify('[0-9]{4}'),
        'farmType' => $faker->word,
        'landline' => $faker->regexify('(0[1-8][1-8])[1-9]{3}\-[0-9]{4}'),
        'mobile' => $faker->regexify('09[0-9]{9}'),
    ];
});

// $factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
//     return [
//         'name' => $faker->word,
//         'type' => $faker->word,
//         'age' => $faker->address,
//         'breed' => $faker->word,
//         'price' => $faker->regexify('[0-9]{4}'),
//         'quantity' => $faker->word,
//         'adg' => $faker->word,
//         'fcr' => $faker->word,
//         'backfat_thickness' => $faker->word,
//         'other_details' => $faker->word,
//     ];
// });
