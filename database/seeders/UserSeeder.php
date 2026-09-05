<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Wastelyn',
            'email' => 'admin@wastelyn.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Depok, Jawa Barat',
            'photo' => null,
            'xp' => 0,
            'points' => 0,
            'level' => 1,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Mitra Wastelyn',
            'email' => 'mitra@wastelyn.com',
            'password' => Hash::make('12345678'),
            'role' => 'mitra',
            'phone' => '081234567891',
            'address' => 'Depok, Jawa Barat',
            'photo' => null,
            'xp' => 0,
            'points' => 0,
            'level' => 1,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Warga Wastelyn',
            'email' => 'warga@wastelyn.com',
            'password' => Hash::make('12345678'),
            'role' => 'warga',
            'phone' => '081234567892',
            'address' => 'Depok, Jawa Barat',
            'photo' => null,
            'xp' => 0,
            'points' => 0,
            'level' => 1,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);
    }
}