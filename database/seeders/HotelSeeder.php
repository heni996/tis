<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\User;
use Faker\Factory as FakerFactory;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hotelManagers = User::role('ROLE_HOTEL_MANAGER')->get();
        $hotelReceptionists = User::role('ROLE_HOTEL_RECEPTIONIST')->get();
        $androidUsers = User::role('ROLE_HOTEL_ANDROID')->get();

        $faker = FakerFactory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $hotel = new Hotel();
            $hotel->id = \Illuminate\Support\Str::uuid(); // Generate UUID for the 'id' field
            $hotel->name = $faker->company;
            $hotel->user_id = $faker->randomElement($hotelManagers)->id; // Set user_id from a random hotel manager
            $hotel->save();

            $hotel->users()->attach($faker->randomElement($hotelReceptionists)); // Change to belongsToMany
            $hotel->users()->attach($faker->randomElement($androidUsers)); // Change to belongsToMany
        }
    }
}
