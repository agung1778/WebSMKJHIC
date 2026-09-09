<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $prefix = 'ADMIN_USER_' . $i;

            $name = env($prefix . '_NAME');
            $email = env($prefix . '_EMAIL');
            $password = env($prefix . '_PASSWORD');
            $role = env($prefix . '_ROLE');

            if ($name && $email && $password && $role) {
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => $role,
                ]);
            }
        }
    }
}