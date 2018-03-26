<?php

use Faker\Generator as Faker;

$factory->define(App\Lendee::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName . ' ' . $faker->lastName,
        'relation' => App\LendeeRelation::inRandomOrder()->first()->id
    ];
});
