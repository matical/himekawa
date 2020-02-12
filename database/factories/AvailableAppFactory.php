<?php

use Faker\Generator as Faker;

$factory->define(himekawa\AvailableApp::class, fn (Faker $faker) => [
    'app_id'       => $faker->randomElement(range(1, 8)),
    'version_code' => $faker->unique()->randomNumber(3),
    'version_name' => sprintf('%d.%d.%d', $faker->randomDigit, $faker->randomDigit, $faker->randomDigit),
    'size'         => $faker->randomNumber(6),
    'hash'         => $faker->sha1,
]);
