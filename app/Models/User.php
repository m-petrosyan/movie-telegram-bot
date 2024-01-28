<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'name',
        'telegraph_bot_id',
    ];

    public function data(): HasOne
    {
        return $this->hasOne(UserData::class);
    }
}
