<?php

namespace App\Repositories;

trait UserRepository
{
    public function userAnswersSumm(): int
    {
        return $this->getChatId()
            ? $this->getUser()->data->correct + $this->getUser()->data->wrong
            : 0;
    }
}
