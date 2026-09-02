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

        if ($user->status !== 'active') {
            Auth::logout();

            $message = match ($user->status) {
                'pending' => $user->role === 'mitra'
                ? 'Akun Mitra kamu masih menunggu persetujuan Admin.'
                : 'Akun kamu masih menunggu persetujuan Admin.',
                'rejected' => $user->role === 'mitra'
                ? 'Pendaftaran Mitra kamu ditolak oleh Admin.'
                : 'Akun kamu ditolak oleh Admin.',
                'inactive' => 'Akun kamu sedang dinonaktifkan oleh Admin.',
                default => 'Akun kamu belum dapat digunakan.',
            };

            return back()
                ->withErrors([
                    'email' => $message,
                ])
                ->withInput($request->only('email'));
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