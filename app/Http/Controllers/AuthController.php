<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'), $request->has('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 🔥 LANGSUNG REDIRECT KE DASHBOARD SESUAI ROLE
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Selamat datang Admin!');
            } elseif ($user->role === 'mitra') {
                return redirect('/mitra/dashboard')->with('success', 'Selamat datang Mitra!');
            } else {
                return redirect('/user/dashboard')->with('success', 'Selamat datang di WasteLyn!');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'warga',
            'level' => 1,
            'xp' => 0,
            'points' => 0,
        ]);

        Auth::login($user);

        return redirect('/user/dashboard')->with('success', 'Registrasi berhasil!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logout berhasil.');
    }
}