<?php

use Faker\Generator as Faker;

$factory->define(himekawa\WatchedApp::class, fn (Faker $faker) => [
    'name'           => $faker->word,
    'slug'           => $faker->slug,
    'original_title' => $faker->words(3, true),
    'package_name'   => $faker->word,
]);
