<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Tourist;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Str;

class TouristSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $tourist = Tourist::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'nationality' => $faker->country,
                'passport_number' => $faker->unique()->randomNumber(8),
                'is_famous' => $faker->boolean,
                'email' => $faker->email,
                'arrival_date' => $faker->dateTimeBetween('-1 month', 'now'),
                'departure_date' => $faker->dateTimeBetween('now', '+1 month'),
                'code' => $faker->unique()->slug(2),
                'is_valid' => $faker->boolean,
            ]);
            $hotelIds = Hotel::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $tourist->hotels()->attach($hotelIds);
        }
    }
}

