<?php

namespace App\Services;

use App\Models\WasteBank;
use Illuminate\Support\Facades\Schema;

class BankSampahService
{
    public function cariTerdekat(?float $lat, ?float $lng, int $limit = 5): array
    {
        $query = WasteBank::query();

        if (Schema::hasColumn('waste_banks', 'status')) {
            $query->where(function ($q) {
                $q->whereIn('status', ['aktif', 'active'])
                    ->orWhereNull('status');
            });
        }

        if ($lat !== null && $lng !== null) {
            $query->selectRaw("
                    *,
                    (6371 * acos(
                        cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )) AS distance_km
                ", [$lat, $lng, $lat])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->having('distance_km', '<', 100)
                ->orderBy('distance_km');
        } else {
            $query->orderBy('name');
        }

        return $query->limit($limit)
            ->get()
            ->map(fn($b) => [
                'nama' => $b->name,
                'alamat' => $b->address,
                'telepon' => $b->phone,
                'email' => $b->email,
                'jam_operasional' => $b->opening_hours,
                'latitude' => $b->latitude,
                'longitude' => $b->longitude,
                'jarak_km' => isset($b->distance_km) ? round((float) $b->distance_km, 1) : null,
            ])
            ->values()
            ->toArray();
    }

    public function cariRelevan(string $pesan, int $limit = 5): array
    {
        $pesanLower = strtolower($pesan);

        $query = WasteBank::query();

        if (Schema::hasColumn('waste_banks', 'status')) {
            $query->where(function ($q) {
                $q->whereIn('status', ['aktif', 'active'])
                    ->orWhereNull('status');
            });
        }

        $semua = $query->limit(200)->get();

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
            'jarak_km' => null,
        ])->values()->toArray();
    }

    public function formatUntukPrompt(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $out = "\n\n=== DATA BANK SAMPAH (WAJIB PAKAI INI, JANGAN MENGARANG) ===\n";

        foreach ($data as $i => $b) {
            $no = $i + 1;
            $out .= "{$no}. {$b['nama']}\n";
            $out .= "   Alamat: {$b['alamat']}\n";

            if (!empty($b['jarak_km'])) {
                $out .= "   Jarak dari user: {$b['jarak_km']} km\n";
            }

            if (!empty($b['telepon'])) {
                $out .= "   Telepon: {$b['telepon']}\n";
            }
            if (!empty($b['email'])) {
                $out .= "   Email: {$b['email']}\n";
            }
            if (!empty($b['jam_operasional'])) {
                $out .= "   Jam buka: {$b['jam_operasional']}\n";
            }

            $out .= "\n";
        }

        return $out;
    }
}