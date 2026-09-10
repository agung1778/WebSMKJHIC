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
     * Password diambil dari environment (.env) untuk menghindari
     * hardcoded credential di dalam source code.
     * Contoh .env:
     *   ADMIN_USER_1_NAME="Pandu"
     *   ADMIN_USER_1_EMAIL="pandunurasih@gmail.com"
     *   ADMIN_USER_1_PASSWORD="..."
     *
     * Role yang tersedia: admin (kelola konten) & superadmin (semua,
     * termasuk manajemen user/role).
     */
    public function run(): void
    {
        $admins = [
            [
                'name'     => env('ADMIN_USER_1_NAME', 'Admin'),
                'email'    => env('ADMIN_USER_1_EMAIL', 'admin@smkamaliah.sch.id'),
                'password' => env('ADMIN_USER_1_PASSWORD'),
                'role'     => env('ADMIN_USER_1_ROLE', 'admin'),
            ],
            [
                'name'     => env('ADMIN_USER_2_NAME', 'Super Admin'),
                'email'    => env('ADMIN_USER_2_EMAIL', 'superadmin@smkamaliah.sch.id'),
                'password' => env('ADMIN_USER_2_PASSWORD'),
                'role'     => env('ADMIN_USER_2_ROLE', 'superadmin'),
            ],
        ];

        foreach ($admins as $admin) {
            // Lewati akun yang tidak memiliki password di environment
            if (blank($admin['password'])) {
                continue;
            }

            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name'     => $admin['name'],
                    'password' => Hash::make($admin['password']),
                    'role'     => $admin['role'],
                ]
            );
        }
    }
}