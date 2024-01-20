<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use Faker\Factory as FakerFactory;

class QuestionSeeder extends Seeder
{
    const QUESTION_TYPES = [
        'int',
        'bool',
        'smiley'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $question = new Question();
            $question->content = $faker->sentence;
            $question->type = $faker->randomElement(self::QUESTION_TYPES);
            $question->save();
        }
    }
}
