<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WasteCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('waste_categories')->insert([
            [
                'name'             => 'Plastik',
                'description'      => 'Botol, gelas, kemasan, dan kantong plastik',
                'price_per_kg'     => 3000,
                'reward_per_kg'    => 2500,
                'point_per_kg'     => 500,
                'co2_saved_per_kg' => 1.5,
                'icon'             => 'plastic.png',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Kertas',
                'description'      => 'Kardus, koran, buku, dan majalah bekas',
                'price_per_kg'     => 2000,
                'reward_per_kg'    => 1800,
                'point_per_kg'     => 300,
                'co2_saved_per_kg' => 0.9,
                'icon'             => 'paper.png',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Logam',
                'description'      => 'Kaleng, besi, tembaga, dan aluminium',
                'price_per_kg'     => 8000,
                'reward_per_kg'    => 7000,
                'point_per_kg'     => 1000,
                'co2_saved_per_kg' => 2.5,
                'icon'             => 'metal.png',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Kaca',
                'description'      => 'Botol kaca dan pecahan kaca',
                'price_per_kg'     => 1500,
                'reward_per_kg'    => 1200,
                'point_per_kg'     => 400,
                'co2_saved_per_kg' => 0.6,
                'icon'             => 'glass.png',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Organik',
                'description'      => 'Sisa makanan, daun, dan bahan kompos',
                'price_per_kg'     => 1000,
                'reward_per_kg'    => 800,
                'point_per_kg'     => 200,
                'co2_saved_per_kg' => 0.4,
                'icon'             => 'organic.png',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}