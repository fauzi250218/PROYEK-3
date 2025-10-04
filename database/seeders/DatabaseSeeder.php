<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔹 Seed User Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'], // cek berdasarkan email
            [
                'name' => 'Admin',
                'password' => Hash::make('password'), // password default
                'role' => 'admin',
            ]
        );

        // 🔹 Seed User Guru
        User::firstOrCreate(
            ['email' => 'guru@example.com'], // cek berdasarkan email
            [
                'name' => 'Guru User',
                'password' => Hash::make('password'), // password default
                'role' => 'guru',
            ]
        );
    }
}
