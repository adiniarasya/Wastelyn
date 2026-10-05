<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->onboarding_completed && $user->waste_bank_id) {
            return redirect()->route('user.dashboard');
        }

        $mitras = User::where('role', 'mitra')
            ->where('status', 'active')
            ->with('managedWasteBank')
            ->get()
            ->filter(
                fn($m) =>
                $m->managedWasteBank &&
                $m->managedWasteBank->latitude &&
                $m->managedWasteBank->longitude
            )
            ->values();

        $mitrasJson = $mitras->map(fn($m) => [
            'bank_id' => $m->managedWasteBank->bank_id,
            'name' => $m->managedWasteBank->name ?? $m->name,
            'address' => $m->managedWasteBank->address,
            'latitude' => (float) $m->managedWasteBank->latitude,
            'longitude' => (float) $m->managedWasteBank->longitude,
        ])->values();

        return view('user.onboarding.index', compact('mitras', 'mitrasJson', 'user'));
    }

    public function nearby(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $lat = (float) $data['lat'];
        $lng = (float) $data['lng'];

        $bank = WasteBank::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("
                bank_id, name, address, latitude, longitude,
                (6371 * acos(
                    LEAST(1, GREATEST(-1,
                        cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    ))
                )) AS jarak_km
            ", [$lat, $lng, $lat])
            ->orderBy('jarak_km')
            ->first();

        if (!$bank) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada bank sampah terdaftar.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'bank_id' => $bank->bank_id,
                'name' => $bank->name,
                'address' => $bank->address,
                'latitude' => (float) $bank->latitude,
                'longitude' => (float) $bank->longitude,
                'jarak_km' => round($bank->jarak_km, 2),
            ],
        ]);
    }

    public function reverseGeocode(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        try {
            $response = Http::timeout(5)
                ->withHeaders(['User-Agent' => 'WasteLyn/1.0'])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat' => $data['lat'],
                    'lon' => $data['lng'],
                    'format' => 'json',
                    'accept-language' => 'id',
                ]);

            $json = $response->json();

            if (!empty($json['display_name'])) {
                return response()->json([
                    'success' => true,
                    'address' => $json['display_name'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak ditemukan.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil alamat.',
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->onboarding_completed && $user->waste_bank_id) {
            return redirect()->route('user.dashboard');
        }

        $validated = $request->validate([
            'address' => 'required|string|max:500',
            'waste_bank_id' => 'required|exists:waste_banks,bank_id',
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->update([
                'address' => $validated['address'],
                'waste_bank_id' => $validated['waste_bank_id'],
                'onboarding_completed' => true,
                'onboarding_completed_at' => now(),
            ]);
        });

        return redirect()->route('user.dashboard')
            ->with('success', 'Setup selesai! Selamat datang di Wastelyn');
    }
}