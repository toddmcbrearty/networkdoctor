<?php

use Faker\Generator as Faker;

$factory->define(App\LendeeRelation::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
