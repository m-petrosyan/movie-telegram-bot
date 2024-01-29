<?php

namespace App\Telegram;

use App\Models\Movie;
use App\Models\MovieAnswer;
use App\Models\User;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;

class TelegramHandler extends WebhookHandler
{
    public function start(): void
    {
        $firstName = $this->message->from()->firstName();

        $this->reply("Hi $firstName 🙌");

        sleep(2);

        $this->reply("The game begins 😎");
        Log::info($this->getChatId());
        $this->question();
    }

    public function question(int $chat_id = null): void
    {
        Log::info('chatId', [$this->getChatId($chat_id)]);

        $chat = User::find($this->getChatId($chat_id));

        $movie = Movie::orderBy('id')->skip($this->userAnswersSumm())->take(1)->first();

        Log::info('movie', [$movie->answer->name]);

        $chat->html(`<img src="movies/$movie->image">`)->send();


        Log::info('testing', ['step1']);
        $this->choice($movie, $chat_id);
    }

    public function choice(object $movie, int $chat_id = null): void
    {
        $chat_id = $this->getChatId($chat_id);

        Log::info('$chat_id', [$chat_id]);

        $currentMovieAnswer = $movie->answer;

        $randomUserIds = MovieAnswer::where('id', '!=', $currentMovieAnswer->id)->inRandomOrder()->take(4)->get();

        Log::info('$currentMovieAnswer', [$currentMovieAnswer->id]);

        $randomAnswers = $randomUserIds->prepend($currentMovieAnswer)->shuffle();

        $answers = [];

        foreach ($randomAnswers as $answer) {
            $answers[] =
                Button::make($answer->name)->action('answer')
                    ->param('is_right', $movie->id === $answer->movie_id)
                    ->param('chat_id', $chat_id);
        }

        Telegraph::message('Answers')
            ->keyboard(Keyboard::make()->buttons($answers)->chunk(2))
            ->send();
    }

    public function answer(): void
    {
        $user = $this->getUser();

        $this->data->get('is_right') ? $user->data->increment('correct') : $user->data->increment('wrong');

        $correct = $user->data->correct;
        $wrong = $user->data->wrong;

        $this->reply("Correct : $correct / Wrong : $wrong");

        sleep(3);

        $this->userAnswersSumm() < Movie::count()
            ? $this->question($this->data->get('chat_id'))
            : $this->endOfTheGame($this->getChatId());
    }

    public function endOfTheGame($chat_id): void
    {
        $user = $this->getUser();
        $correct = $user->data->correct;
        $wrong = $user->data->wrong;

        Telegraph::message('End of the game 😉')->send();

        Telegraph::message("Result | correct : $correct / wrong : $wrong")->send();

        Telegraph::message('Menu')
            ->keyboard(
                Keyboard::make()->buttons([
                    Button::make('Reset results and start again')->action('reset')->param('chat_id', $chat_id),
                ])
            )->send();
    }

    public function reset(): void
    {
        $user = $this->getUser();

        $user->data->correct = 0;
        $user->data->wrong = 0;
        $user->data->save();

        $this->question();
    }

    public function getChatId(int $chat_id = null): int
    {
        return $chat_id ?? $this->message?->from()->id() ?? $this->data->get('chat_id');
    }

    public function getUser()
    {
        return User::where('chat_id', $this->getChatId())->first();
    }

    public function userAnswersSumm(): int
    {
        return $this->getChatId()
            ? $this->getUser()->data->pluck('correct')->sum() + $this->getUser()->data->pluck('wrong')->sum()
            : 0;
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->reply("Unknown command");
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $this->reply("I'm starting to search 🔍 '$text'");
    }

    public function info(): void
    {
        Telegraph::message('Menu')
            ->keyboard(
                Keyboard::make()->buttons([
                    Button::make('My portfolio')->url('https://mpetrosyan.com'),
                    Button::make('Project repository')->url('https://github.com/m-petrosyan/movie-telegram-bot'),
                ])
            )->send();
    }
}
