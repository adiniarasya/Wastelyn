<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WasteBankSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('waste_banks')->insert([
            [
                'mitra_id' => 1,
                'name' => 'Bank Sampah Wastelyn Pusat',
                'address' => 'Jl. Merdeka No. 1, Jakarta',
                'phone' => '021-1234567',
                'email' => 'pusat@wastelyn.id',
                'latitude' => -6.2000000,
                'longitude' => 106.8166667,
                'opening_hours' => '08:00 - 17:00',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mitra_id' => 2,
                'name' => 'Bank Sampah Wastelyn Bandung',
                'address' => 'Jl. Asia Afrika No. 10, Bandung',
                'phone' => '022-7654321',
                'email' => 'bandung@wastelyn.id',
                'latitude' => -6.9174639,
                'longitude' => 107.6191228,
                'opening_hours' => '08:00 - 16:00',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mitra_id' => 3,
                'name' => 'Bank Sampah Wastelyn Surabaya',
                'address' => 'Jl. Tunjungan No. 5, Surabaya',
                'phone' => '031-5551234',
                'email' => 'surabaya@wastelyn.id',
                'latitude' => -7.2574719,
                'longitude' => 112.7520883,
                'opening_hours' => '08:00 - 17:00',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}