<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'ROLE_SUPER_ADMIN',
            'ROLE_ADMIN',
            'ROLE_HOTEL_MANAGER',
            'ROLE_HOTEL_RECEPTIONIST',
            'ROLE_HOTEL_ANDROID',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
