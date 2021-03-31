<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {
    $time = $faker->dateTimeThisMonth();




    return [
        'content' => $faker->sentence(),
        'created_at' => $time,
        'updated_at' => $time,
        'topic_id' => rand(1, 100),
        'user_id' => rand(1, 10),
    ];
});
