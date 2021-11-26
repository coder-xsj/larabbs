<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Friend;
use Faker\Generator as Faker;

$factory->define(Friend::class, function (Faker $faker) {
    $avatars = [
        'https://cdn.learnku.com/uploads/images/201710/14/1/s5ehp11z6s.png',
        'https://cdn.learnku.com/uploads/images/201710/14/1/Lhd1SHqu86.png',
        'https://cdn.learnku.com/uploads/images/201710/14/1/LOnMrqbHJn.png',
        'https://cdn.learnku.com/uploads/images/201710/14/1/xAuDMxteQy.png',
        'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png',
        'https://cdn.learnku.com/uploads/images/201710/14/1/NDnzMutoxX.png',
    ];
    return [
        //
        'name' => $faker->name,
        'link' => $faker->url,
        'avatar' => $faker->randomElement($avatars),
    ];
});
