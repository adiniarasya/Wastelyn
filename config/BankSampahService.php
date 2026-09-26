<?php

namespace App\Services;

use App\Models\WasteBank;

class BankSampahService
{
    public function cariRelevan(string $pesan, int $limit = 5): array
    {
        $pesanLower = strtolower($pesan);

        // Ambil semua bank sampah aktif
        $query = WasteBank::query();

        // Filter kalau status memungkinkan
        if (\Schema::hasColumn('wastebank', 'status')) {
            $query->where(function ($q) {
                $q->where('status', 'aktif')
                    ->orWhere('status', 'active')
                    ->orWhereNull('status');
            });
        }

        $semua = $query->get();

        // Coba cocokkan nama kota/kecamatan dari alamat ke pesan user
        $matched = $semua->filter(function ($b) use ($pesanLower) {
            $alamat = strtolower($b->address ?? '');
            $nama = strtolower($b->name ?? '');

            foreach (preg_split('/[\s,]+/', $alamat) as $kata) {
                if (strlen($kata) >= 4 && str_contains($pesanLower, $kata)) {
                    return true;
                }
            }
            foreach (preg_split('/[\s,]+/', $nama) as $kata) {
                if (strlen($kata) >= 4 && str_contains($pesanLower, $kata)) {
                    return true;
                }
            }
            return false;
        });

        $hasil = $matched->isNotEmpty()
            ? $matched->take($limit)
            : $semua->take($limit);

        return $hasil->map(fn($b) => [
            'nama' => $b->name,
            'alamat' => $b->address,
            'telepon' => $b->phone,
            'email' => $b->email,
            'jam_operasional' => $b->opening_hours,
            'latitude' => $b->latitude,
            'longitude' => $b->longitude,
        ])->values()->toArray();
    }

    public function formatUntukPrompt(array $data): string
    {
        if (empty($data))
            return '';

        $out = "\n\n=== DATA BANK SAMPAH (WAJIB PAKAI INI, JANGAN MENGARANG) ===\n";
        foreach ($data as $i => $b) {
            $no = $i + 1;
            $out .= "{$no}. {$b['nama']}\n";
            $out .= "   Alamat: {$b['alamat']}\n";
            if (!empty($b['telepon']))
                $out .= "   Telepon: {$b['telepon']}\n";
            if (!empty($b['email']))
                $out .= "   Email: {$b['email']}\n";
            if (!empty($b['jam_operasional']))
                $out .= "   Jam buka: {$b['jam_operasional']}\n";
            $out .= "\n";
        }
        return $out;
    }
}