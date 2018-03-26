<?php

use Faker\Generator as Faker;

$factory->define(App\Device::class, function (Faker $faker) {
    $deviceId = $faker->numberBetween(0, 4);
    $deviceTypes = App\DeviceType::get()->toArray();

    return [
        'device_id' => $faker->uuid,
        'name' => $deviceTypes[$deviceId]['name'],
        'device_type' => $deviceId,
    ];
});
