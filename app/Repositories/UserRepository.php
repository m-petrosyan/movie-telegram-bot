<?php

namespace App\Repositories;

use App\Models\User;

trait UserRepository
{
    public function getUser()
    {
        return User::where('chat_id', $this->getChatId())->first();
    }

    public function userAnswersSumm(): int
    {
        return $this->getChatId()
            ? $this->getUser()->data->correct + $this->getUser()->data->wrong
            : 0;
    }
}
