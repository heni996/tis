<?php

namespace Database\Seeders;

use App\Models\GuestBook;
use App\Models\Hotel;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class GuestBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hotels = Hotel::all();
        $faker = FakerFactory::create('fr_FR');

        foreach ($hotels as $hotel) {
            $nbEntries = mt_rand(10, 30);
            for ($i = 0; $i < $nbEntries; $i++) {
                $guestBook = new GuestBook();
                $guestBook->hotel_id = $hotel->id;
                $guestBook->language = $faker->languageCode;
                $guestBook->country = $faker->country;
                $guestBook->client_first_name = $faker->firstName;
                $guestBook->client_last_name = $faker->lastName;

                if ($faker->boolean(50)) {
                    $guestBook->email = $faker->email;
                }

                if ($faker->boolean(50)) {
                    $guestBook->phone_number = $faker->phoneNumber;
                }

                if ($faker->boolean(50)) {
                    $guestBook->extra_comment = $faker->text;
                }

                $guestBook->save();
            }
        }
    }
}
