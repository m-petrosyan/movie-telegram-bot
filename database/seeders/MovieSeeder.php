<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = [
            [
                'name' => 'Interstellar',
                'image' => 'interstellar.webp',
            ],
            [
                'name' => 'Forest Gump',
                'image' => 'forest_gump.webp',
            ],
            [
                'name' => 'Dune: Part One',
                'image' => 'dune.webp',
            ],
            [
                'name' => 'The Wolf of Wall Street',
                'image' => 'wolf.webp',
            ],
            [
                'name' => "Schindler's List",
                'image' => 'schindler.webp',
            ],
            [
                'name' => 'The Terminal',
                'image' => 'terminal.webp',
            ],
            [
                'name' => 'Léon',
                'image' => 'leon.webp',
            ],
            [
                'name' => 'The Fifth Element',
                'image' => 'the_fifth_element.webp',
            ],
            [
                'name' => 'Groundhog Day',
                'image' => 'groundhog_day.webp',
            ],
            [
                'name' => 'The Hateful Eight',
                'image' => 'eight.webp',
            ],
            [
                'name' => 'Django Unchained',
                'image' => 'django.webp',
            ],
            [
                'name' => 'Interview with the Vampire: The Vampire Chronicles',
                'image' => 'interview.webp',
            ],
            [
                'name' => 'Sin City',
                'image' => 'sin_city.webp',
            ],
            [
                'name' => 'Reservoir Dogs',
                'image' => 'reservoir_dogs.webp',
            ],
            [
                'name' => 'The Big Lebowski',
                'image' => 'lebowski.webp',
            ],
            [
                'name' => 'The Mist',
                'image' => 'mist.webp',
            ],
            [
                'name' => "Harry Potter and the Sorcerer's Stone:",
                'image' => 'harry_potter.webp',
            ],
            [
                'name' => 'Inception',
                'image' => 'inception.webp',
            ],
            [
                'name' => 'The Thing',
                'image' => 'thing.webp',
            ],
            [
                'name' => 'The Great Gatsby',
                'image' => 'gatsby.webp',
            ],
            [
                'name' => 'Liar Liar',
                'image' => 'liar.webp',
            ],
            [
                'name' => 'Oldeuboi',
                'image' => 'oldeuboi.webp',
            ],
            [
                'name' => 'Dogville',
                'image' => 'dogville.webp',
            ],
        ];

        foreach ($movies as $movie) {
            if (!Movie::where('image', $movie['image'])->exists()) {
                $move = Movie::create(['image' => $movie['image']]);
                $move->answer()->create(['name' => $movie['name']]);
            }
        }
    }
}
