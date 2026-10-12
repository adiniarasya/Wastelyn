<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\WasteBank;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'mitra') {
            $wasteBank = WasteBank::where('mitra_id', $user->id)->first();

            return view('profile.mitra', [
                'user' => $user,
                'wasteBank' => $wasteBank,
            ]);
        }

        $wasteBankList = WasteBank::where('status', 'active')
            ->get()
            ->map(function ($wb) use ($user) {
                $wb->jarak = $user->jarakKe(
                    (float) $wb->latitude,
                    (float) $wb->longitude
                );
                return $wb;
            })
            ->sortBy(fn($wb) => $wb->jarak ?? PHP_FLOAT_MAX)
            ->values();

        return view('profile.warga', [
            'user' => $user,
            'wasteBankList' => $wasteBankList,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function gantiBankSampah(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'warga') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya warga yang bisa mengganti bank sampah.',
            ], 403);
        }

        $request->validate([
            'waste_bank_id' => ['required', 'exists:waste_banks,id'],
        ]);

        $user->waste_bank_id = $request->waste_bank_id;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Bank sampah berhasil diganti',
            'waste_bank' => $user->load('wasteBank')->wasteBank,
        ]);
    }

    public function updateBankSampah(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_if($user->role !== 'mitra', 403, 'Hanya mitra yang bisa mengelola bank sampah.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'opening_hours' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $wasteBank = WasteBank::where('mitra_id', $user->id)->firstOrFail();
        $wasteBank->update($validated);

        return Redirect::route('profile.edit')->with('status', 'bank-updated');
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah',
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}