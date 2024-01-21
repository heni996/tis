<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $this->assignRoles($faker->firstName, $faker->lastName);
    }

    private function assignRoles($firstName, $lastName): void
    {
        $rolesMap = [
            'superadmin' => 'ROLE_SUPER_ADMIN',
            'admin' => 'ROLE_ADMIN',
            'hotelmanager' => 'ROLE_HOTEL_MANAGER',
            'hotelreceptionist' => 'ROLE_HOTEL_RECEPTIONIST',
            'android' => 'ROLE_HOTEL_ANDROID',
        ];

        foreach ($rolesMap as $roleType => $role) {
            for ($i = 0; $i < 3; $i++) {
                $role = Role::where('name', $role)->first();
                $email = "{$roleType}{$i}@tis.tn";
                $user= User::create([
                    'id' => Str::uuid(),
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'password' => Hash::make('password_123'),
                ]);
                if ($user) {
                    $user->assignRole($role);
                    if ($roleType === 'hotelmanager' || $roleType === 'hotelreceptionist' || $roleType === 'android') {
                        $hotel = Hotel::inRandomOrder()->first(); // Get a random hotel
                        $user->hotel_id = $hotel->id;
                        $user->save();
                    }
                }
            }
        }
    }
}
