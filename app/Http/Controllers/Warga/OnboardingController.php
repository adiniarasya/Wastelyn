<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use App\Models\User;

class OnboardingController extends Controller
{
    public function index()
{
    $mitras = User::where('role', 'mitra')
        ->where('status', 'active')
        ->with('managedWasteBank')
        ->get();

    return view('user.onboarding.index', compact('mitras'));
}

public function store(Request $request)
{
    $request->validate([
        'address' => 'required|string|max:500',
        'waste_bank_id' => 'required|exists:waste_banks,bank_id',
    ]);

    auth()->user()->update([
        'address' => $request->address,
        'waste_bank_id' => $request->waste_bank_id,
        'onboarding_completed' => true,
    ]);

    return redirect()->route('user.dashboard')
        ->with('success', 'Setup selesai! Selamat datang di Wastelyn 🌱');
}
}