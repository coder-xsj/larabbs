<?php

namespace App\Observers;

use App\Models\User;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{
    public function saving(User $user)
    {
        //
        if(empty($user->avatar)){
            $user->avatar = 'https://cdn.learnku.com/uploads/images/201710/14/1/s5ehp11z6s.png';
        }
    }

    public function updating(User $user)
    {
        //
    }
}
