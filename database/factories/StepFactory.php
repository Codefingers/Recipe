<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Step::class, function (Faker $faker) {
    return [
        'step' => $faker->text,
        'order' => $faker->numberBetween(1, 10),
        'recipe_id' => factory(\App\Models\Recipe::class),
    ];
});
