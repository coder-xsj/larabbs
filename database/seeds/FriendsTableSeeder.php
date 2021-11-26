<?php

use Illuminate\Database\Seeder;
use App\Models\Friend;

class FriendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $friends = factory(Friend::class)->times(6)->create();

    }
}
