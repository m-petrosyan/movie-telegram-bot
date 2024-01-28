<?php

namespace App\Observers\User;

use App\Models\User1;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User1 $user): void
    {
        $user->data()->create();
    }

}
