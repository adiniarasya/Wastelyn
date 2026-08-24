<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============ LANDING PAGE ============
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ============ REDIRECT /home KE DASHBOARD ============
Route::get('/home', function () {
    $user = auth()->user();
    if ($user) {
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'mitra') {
            return redirect('/mitra/dashboard');
        }
        return redirect('/user/dashboard');
    }
    return redirect('/login');
});

// ============ GUEST ROUTES (Belum Login) ============
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ============ AUTH ROUTES (Sudah Login) ============
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });

    // Mitra Routes
    Route::middleware('role:mitra')->prefix('mitra')->name('mitra.')->group(function () {
        Route::get('/dashboard', function () {
            return view('mitra.dashboard');
        })->name('dashboard');
    });

    // Warga Routes
    Route::middleware('role:warga')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');
    });
});