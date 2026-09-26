<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WasteBank;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'role' => ['required', 'in:warga,mitra'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($request->role === 'mitra') {
            $rules['phone'] = ['required', 'string', 'max:20'];
            $rules['address'] = ['required', 'string'];
            $rules['latitude'] = ['nullable', 'numeric', 'between:-90,90'];
            $rules['longitude'] = ['nullable', 'numeric', 'between:-180,180'];
        }

        $validated = $request->validate($rules);

        $status = $validated['role'] === 'mitra' ? 'pending' : 'active';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $status,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'xp' => 0,
            'points' => 0,
            'level' => 1,
        ]);

        if ($user->role === 'mitra') {
            // 1. PRIORITAS: koordinat dari peta
            $lat = $validated['latitude'] ?? null;
            $lng = $validated['longitude'] ?? null;

            // 2. FALLBACK: geocode dari alamat kalau user tidak pilih titik di peta
            if (!$lat || !$lng) {
                $coords = $this->geocodeAddress($validated['address']);
                $lat = $coords['lat'];
                $lng = $coords['lng'];
            }

            // 3. Kalau tetap tidak dapat, rollback user dan tolak registrasi
            if (!$lat || !$lng) {
                $user->delete();

                return back()->withErrors([
                    'latitude' => 'Lokasi tidak bisa ditentukan. Silakan pilih titik di peta atau isi alamat yang lebih spesifik.',
                ])->withInput();
            }

            WasteBank::create([
                'mitra_id' => $user->user_id,
                'name' => $validated['name'] . ' Bank Sampah',
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'latitude' => $lat,
                'longitude' => $lng,
                'opening_hours' => '08:00 - 17:00',
                'status' => 'pending',
            ]);
        }

        event(new Registered($user));

        if ($user->role === 'mitra') {
            return redirect()
                ->route('login')
                ->with('status', 'Pendaftaran sebagai Mitra berhasil. Akun kamu sedang menunggu persetujuan Admin.');
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    private function geocodeAddress(string $address): array
    {
        $queries = array_unique(array_filter([
            $address,
            $this->simplifyAddress($address, 3),
            $this->simplifyAddress($address, 2),
        ]));

        foreach ($queries as $q) {
            try {
                $response = Http::timeout(5)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'address' => $q,
                    'key' => config('services.google_maps.key'),
                    'language' => 'id',
                    'region' => 'id',
                ]);

                $data = $response->json();

                if (($data['status'] ?? null) === 'OK' && !empty($data['results'][0]['geometry']['location'])) {
                    $loc = $data['results'][0]['geometry']['location'];

                    return [
                        'lat' => (float) $loc['lat'],
                        'lng' => (float) $loc['lng'],
                    ];
                }
            } catch (\Throwable $e) {
                // lanjut ke query berikutnya
            }
        }

        return [
            'lat' => null,
            'lng' => null,
        ];
    }

    private function simplifyAddress(string $address, int $lastParts): string
    {
        $parts = array_values(array_filter(
            array_map('trim', explode(',', $address)),
            fn($p) => strlen($p) > 2
        ));

        if (count($parts) <= $lastParts) {
            return implode(', ', $parts);
        }

        return implode(', ', array_slice($parts, -$lastParts));
    }
}