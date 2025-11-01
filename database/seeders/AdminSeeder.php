<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Pandu',
            'email' => 'pandunurasih@gmail.com',
            'password' => Hash::make('AmdevJHIC090406_#'),
        ]); 

        User::create([
            'name' => 'Admin',
            'email' => 'smkamaliahciawi@gmail.com',
            'password' => Hash::make('smkamaliah12_'),
        ]); 

        User::create([
            'name' => 'Amdev',
            'email' => 'amdevjhic@gmail.com',
            'password' => Hash::make('AmdevJHIC090406_#'),
        ]); 

    }
}