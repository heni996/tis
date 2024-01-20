<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GuestBook;
use App\Models\Question;
use App\Models\Response;
use Faker\Factory as FakerFactory;

class ResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guestBooks = GuestBook::all();
        $questions = Question::all();
        $faker = FakerFactory::create('fr_FR');

        foreach ($guestBooks as $guestBook) {
            foreach ($questions as $question) {
                if (!$faker->boolean(70)) {
                    continue;
                }

                $response = new Response();
                $response->guest_book_id = $guestBook->id;
                $response->question_id = $question->id;

                if ($question->type === 'bool') {
                    $response->value = rand(0, 1);
                } elseif ($question->type === 'int') {
                    $response->value = rand(100, 9999);
                } elseif ($question->type === 'smiley') {
                    $response->value = rand(1, 3);
                } else {
                    $response->value = 'Default value';
                }

                $response->save();
            }
        }
    }
}
