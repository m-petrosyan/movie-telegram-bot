<?php

namespace App\Models;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends TelegraphChat
{
    public function data(): HasOne
    {
        return $this->hasOne(UserData::class);
    }
}
