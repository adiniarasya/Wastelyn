<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        /*
         * Pastikan role yang dipilih di form login
         * sama dengan role yang ada di database.
         */
        if ($user->role !== $request->role) {
            Auth::logout();

            return back()
                ->withErrors([
                    'role' => 'Role yang dipilih tidak sesuai dengan akun ini.',
                ])
                ->withInput($request->only('email', 'role'));
        }

        /*
         * MITRA YANG BELUM DISETUJUI
         */
        if ($user->role === 'mitra' && $user->status !== 'approved') {
            Auth::logout();

            $message = match ($user->status) {
                'pending' => 'Akun Mitra kamu masih menunggu persetujuan Admin.',
                'rejected' => 'Pendaftaran Mitra kamu ditolak oleh Admin.',
                default => 'Akun Mitra kamu belum dapat digunakan.',
            };

            return back()
                ->withErrors([
                    'email' => $message,
                ])
                ->withInput($request->only('email', 'role'));
        }

        $request->session()->regenerate();

        return match ($user->role) {
            'admin' => redirect()->intended('/admin/dashboard'),

            'mitra' => redirect()->intended('/mitra/dashboard'),

            'warga' => redirect()->intended('/user/dashboard'),

            default => redirect('/'),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
