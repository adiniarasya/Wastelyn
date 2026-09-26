<?php

namespace App\Http\Controllers;

use App\Models\WasteBank;
use App\Models\User;
use Illuminate\Http\Request;

class WasteBankController extends Controller
{
    public function index()
    {
        $wasteBanks = WasteBank::with('mitra')->get();
        return view('admin.waste-banks.index', compact('wasteBanks'));
    }

    public function create()
    {
        $mitras = User::where('role', 'mitra')->get();
        return view('admin.waste-banks.create', compact('mitras'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mitra_id' => 'nullable|exists:users,user_id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'opening_hours' => 'nullable|string|max:255',
            'status' => 'required|in:pending,active,inactive',
        ]);

        WasteBank::create($validated);

        return redirect()
            ->route('admin.waste-banks.index')
            ->with('success', 'Bank sampah berhasil ditambahkan');
    }

    public function show(WasteBank $wasteBank)
    {
        $wasteBank->load('mitra', 'pickupRequests');
        return view('admin.waste-banks.show', compact('wasteBank'));
    }

    public function edit(WasteBank $wasteBank)
    {
        $mitras = User::where('role', 'mitra')->get();
        return view('admin.waste-banks.edit', compact('wasteBank', 'mitras'));
    }

    public function update(Request $request, WasteBank $wasteBank)
    {
        $validated = $request->validate([
            'mitra_id' => 'nullable|exists:users,user_id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'opening_hours' => 'nullable|string|max:255',
            'status' => 'required|in:pending,active,inactive',
        ]);

        $wasteBank->update($validated);

        return redirect()
            ->route('admin.waste-banks.index')
            ->with('success', 'Bank sampah berhasil diupdate');
    }

    public function destroy(WasteBank $wasteBank)
    {
        $wasteBank->delete();

        return redirect()
            ->route('admin.waste-banks.index')
            ->with('success', 'Bank sampah berhasil dihapus');
    }

    public function userIndex()
    {
        $wasteBanks = WasteBank::where('status', 'active')
            ->orderBy('name')
            ->get();

        $mapMarkers = $wasteBanks
            ->filter(fn($b) => $b->latitude !== null && $b->longitude !== null)
            ->map(fn($b) => [
                'bank_id' => $b->bank_id,
                'name' => $b->name,
                'address' => $b->address,
                'phone' => $b->phone,
                'hours' => $b->opening_hours,
                'lat' => (float) $b->latitude,
                'lng' => (float) $b->longitude,
            ])
            ->values();

        return view('user.waste-banks.index', compact('wasteBanks', 'mapMarkers'));
    }

    public function userShow(WasteBank $wasteBank)
    {
        $wasteBank->load('pickupRequests');
        return view('user.waste-banks.show', compact('wasteBank'));
    }

    public function setPreferred(Request $request)
    {
        $request->validate([
            'bank_id' => ['required', 'exists:waste_banks,bank_id'],
        ]);

        $bank = WasteBank::where('bank_id', $request->bank_id)
            ->where('status', 'active')
            ->first();

        if (!$bank) {
            return response()->json([
                'ok' => false,
                'error' => 'Bank sampah tidak tersedia atau belum di-approve.',
            ], 422);
        }

        $request->user()->update([
            'preferred_bank_id' => $bank->bank_id,
            'preferred_bank_set_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}