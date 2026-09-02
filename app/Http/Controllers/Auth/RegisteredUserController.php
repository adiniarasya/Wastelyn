<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class
            ],

            'role' => [
                'required',
                'in:warga,mitra'
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults()
            ],
        ]);

        // Warga langsung disetujui.
        // Mitra harus menunggu persetujuan admin.
        $status = $request->role === 'mitra'
            ? 'pending'
            : 'approved';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $status,
            'xp' => 0,
            'points' => 0,
            'level' => 1,
        ]);

        event(new Registered($user));

        /*
         * MITRA
         *
         * Jangan langsung login karena masih menunggu
         * persetujuan Admin.
         */
        if ($user->role === 'mitra') {
            return redirect()
                ->route('login')
                ->with(
                    'status',
                    'Pendaftaran sebagai Mitra berhasil. Akun kamu sedang menunggu persetujuan Admin.'
                );
        }

        /*
         * WARGA
         *
         * Warga langsung login.
         */
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}

