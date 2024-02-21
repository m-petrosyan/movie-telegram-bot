<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\MovieAnswer;

trait MovieRepository
{
    public function getCurrentMovie()
    {
        return Movie::orderBy('id')->skip($this->userAnswersSumm())->take(1)->first();
    }

    public function getRandomMovie(int $currentMovieAnswerId)
    {
        return MovieAnswer::where('id', '!=', $currentMovieAnswerId)->inRandomOrder()->take(4)->get();
    }
}
