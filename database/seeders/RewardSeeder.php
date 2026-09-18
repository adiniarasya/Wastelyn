<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rewards')->insert([
            [
                'name' => 'Pulsa Rp 10.000',
                'description' => 'Tukar poin dengan pulsa semua operator',
                'point_required' => 1000,
                'stock' => 50,
                'image' => 'pulsa-10k.png',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Voucher Belanja Rp 25.000',
                'description' => 'Voucher minimarket',
                'point_required' => 2500,
                'stock' => 30,
                'image' => 'voucher-25k.png',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tumbler Eco',
                'description' => 'Tumbler ramah lingkungan',
                'point_required' => 5000,
                'stock' => 20,
                'image' => 'tumbler.png',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tas Kanvas Daur Ulang',
                'description' => 'Tas kanvas hasil daur ulang',
                'point_required' => 4000,
                'stock' => 15,
                'image' => 'tas-kanvas.png',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Saldo E-Wallet Rp 50.000',
                'description' => 'Top up saldo e-wallet',
                'point_required' => 5000,
                'stock' => 10,
                'image' => 'ewallet-50k.png',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}