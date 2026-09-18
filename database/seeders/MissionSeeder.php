<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('missions')->insert([
            [
                'title' => 'Setor Sampah Pertama',
                'description' => 'Lakukan setoran sampah pertamamu di bank sampah',
                'target' => 1,
                'reward_xp' => 50,
                'reward_points' => 100,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'status' => 'active',
                'bank_id' => 1,
                'type' => 'quantitative',
                'unit' => 'transaksi',
                'ai_prompt' => 'Deteksi apakah user telah melakukan minimal 1 transaksi setoran sampah hari ini.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Setor 5 kg Plastik',
                'description' => 'Kumpulkan dan setor 5 kg sampah plastik',
                'target' => 5,
                'reward_xp' => 100,
                'reward_points' => 500,
                'start_date' => now()->startOfWeek(),
                'end_date' => now()->endOfWeek(),
                'status' => 'active',
                'bank_id' => 1,
                'type' => 'quantitative',
                'unit' => 'kg',
                'ai_prompt' => 'Hitung total berat sampah plastik yang disetor user dalam periode minggu ini.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Setor Sampah 7 Hari Berturut-turut',
                'description' => 'Konsisten setor sampah selama 7 hari berturut-turut',
                'target' => 7,
                'reward_xp' => 200,
                'reward_points' => 1000,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'status' => 'active',
                'bank_id' => 1,
                'type' => 'quantitative',
                'unit' => 'hari',
                'ai_prompt' => 'Cek apakah user setor sampah setiap hari selama 7 hari berturut-turut tanpa bolong.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Ajak 1 Teman',
                'description' => 'Ajak teman bergabung lewat kode referral',
                'target' => 1,
                'reward_xp' => 75,
                'reward_points' => 300,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->addMonths(3)->endOfMonth(),
                'status' => 'active',
                'bank_id' => 2,
                'type' => 'qualitative',
                'unit' => 'orang',
                'ai_prompt' => 'Verifikasi user berhasil mengajak 1 teman yang sudah terdaftar dengan kode referralnya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Setor 10 kg Kertas',
                'description' => 'Kumpulkan 10 kg sampah kertas dalam sebulan',
                'target' => 10,
                'reward_xp' => 150,
                'reward_points' => 800,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'status' => 'active',
                'bank_id' => 1,
                'type' => 'quantitative',
                'unit' => 'kg',
                'ai_prompt' => 'Akumulasi berat sampah kertas yang disetor user selama bulan ini.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}