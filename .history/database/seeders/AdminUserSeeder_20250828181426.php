<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
u

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'administrator@mail.com'],
            [
                'name' => 'Admin Kostify',
                'password' => Hash::make('12345678'),
                'role' => 'ADMIN',
            ]
        );
    }
}
