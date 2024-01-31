<?php

namespace App\Telegram;

use App\Models\Movie;
use App\Models\MovieAnswer;
use App\Models\User;
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

        $this->userAnswersSumm() < Movie::count()
            ? $this->question()
            : $this->endOfTheGame();

    }

    public function question(): void
    {
        $movie = Movie::orderBy('id')->skip($this->userAnswersSumm())->take(1)->first();

        $this->chat->photo("movies/$movie->image")
            ->html('What movie is this shot from?')
            ->send();

        $this->choice($movie);
    }

    public function choice(object $movie): void
    {
        $currentMovieAnswer = $movie->answer;

        $randomUserIds = MovieAnswer::where('id', '!=', $currentMovieAnswer->id)->inRandomOrder()->take(4)->get();

        $randomAnswers = $randomUserIds->prepend($currentMovieAnswer)->shuffle();

        $answers = [];

        foreach ($randomAnswers as $answer) {
            $answers[] =
                Button::make($answer->name)->action('answer')
                    ->param('is_right', $movie->id === $answer->movie_id);
        }

        $chat = $this->chat->message('Answers')
            ->keyboard(Keyboard::make()->buttons($answers)->chunk(2))
            ->send();

        Log::info('msg', [$chat->message_id]);
//        $this->chat->deleteMessage($chat->id);
    }

    public function answer(): void
    {
        $user = $this->getUser();

        $this->data->get('is_right') ? $user->data->increment('correct') : $user->data->increment('wrong');

        $correct = $user->data->correct;
        $wrong = $user->data->wrong;

        $this->reply("Correct : $correct / Wrong : $wrong");

        sleep(1);

        $this->userAnswersSumm() < Movie::count()
            ? $this->question($this->data->get('chat_id'))
            : $this->endOfTheGame();
    }

    public function endOfTheGame(): void
    {
        $user = $this->getUser();
        $correct = $user->data->correct;
        $wrong = $user->data->wrong;

        $this->chat->message('End of the game 😉')->send();

        $this->chat->message("Result | correct : $correct / wrong : $wrong")->send();

        $this->chat->message('Menu')
            ->keyboard(
                Keyboard::make()->buttons([
                    Button::make('Reset results and start again')->action('reset'),
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

    public function getChatId(): int
    {
        return  $this->chat->chat_id;
    }

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
        $this->chat->message('Menu')
            ->keyboard(
                Keyboard::make()->buttons([
                    Button::make('My portfolio')->url('https://mpetrosyan.com'),
                    Button::make('Project repository')->url('https://github.com/m-petrosyan/movie-telegram-bot'),
                ])
            )->send();
    }

    protected function handleChatMemberJoined(User|\DefStudio\Telegraph\DTO\User $member): void
    {
        $this->chat->html("Welcome {$member->firstName()}")->send();
    }
}
