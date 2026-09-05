<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WasteBankController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\UserMissionController;
use App\Http\Controllers\PickupRequestController;
use App\Http\Controllers\AiChatSessionController;
use App\Http\Controllers\AiChatMessageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');


Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

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

Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Statistik
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/export-pdf', [AdminUserController::class, 'exportPdf'])->name('users.export.pdf');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::patch('/users/{id}/reject', [AdminUserController::class, 'reject'])->name('users.reject');

    // Mission Management
    Route::get('/missions', [MissionController::class, 'index'])->name('missions.index');
    Route::get('/missions/create', [MissionController::class, 'create'])->name('missions.create');
    Route::post('/missions', [MissionController::class, 'store'])->name('missions.store');
    Route::get('/missions/{mission}', [MissionController::class, 'show'])->name('missions.show');
    Route::get('/missions/{mission}/edit', [MissionController::class, 'edit'])->name('missions.edit');
    Route::put('/missions/{mission}', [MissionController::class, 'update'])->name('missions.update');
    Route::delete('/missions/{mission}', [MissionController::class, 'destroy'])->name('missions.destroy');

    // Reward Management
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/create', [RewardController::class, 'create'])->name('rewards.create');
    Route::post('/rewards', [RewardController::class, 'store'])->name('rewards.store');
    Route::get('/rewards/{reward}', [RewardController::class, 'show'])->name('rewards.show');
    Route::get('/rewards/{reward}/edit', [RewardController::class, 'edit'])->name('rewards.edit');
    Route::put('/rewards/{reward}', [RewardController::class, 'update'])->name('rewards.update');
    Route::delete('/rewards/{reward}', [RewardController::class, 'destroy'])->name('rewards.destroy');

    // Transaction (Setoran)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');

    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth', 'role:mitra'])->prefix('mitra')->name('mitra.')->group(function () {

    Route::get('/dashboard', [MitraController::class, 'dashboard'])->name('dashboard');

Route::get('/pickup-requests', [PickupRequestController::class, 'mitraIndex'])
    ->name('pickup-requests.index');
    Route::get('/pickup-requests/{id}', [PickupRequestController::class, 'mitraShow'])->name('pickup-requests.show');
    Route::put('/pickup-requests/{id}/status', [PickupRequestController::class, 'updateStatus'])->name('pickup-requests.status');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('transactions.status');

    Route::get('/statistics', [MitraController::class, 'statistics'])->name('statistics');

    Route::get('/profile', [MitraController::class, 'profile'])->name('profile');
    Route::put('/profile', [MitraController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware('role:warga')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::get('/waste-banks', [WasteBankController::class, 'index'])->name('waste-banks.index');
    Route::get('/waste-banks/{id}', [WasteBankController::class, 'show'])->name('waste-banks.show');

    Route::get('/pickup-requests', [PickupRequestController::class, 'index'])->name('pickup-requests.index');
    Route::get('/pickup-requests/create', [PickupRequestController::class, 'create'])->name('pickup-requests.create');
    Route::post('/pickup-requests', [PickupRequestController::class, 'store'])->name('pickup-requests.store');
    Route::get('/pickup-requests/{id}', [PickupRequestController::class, 'show'])->name('pickup-requests.show');
    Route::delete('/pickup-requests/{id}', [PickupRequestController::class, 'destroy'])->name('pickup-requests.destroy');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');

    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/{id}', [RewardController::class, 'show'])->name('rewards.show');

    Route::get('/missions', [MissionController::class, 'index'])->name('missions.index');
    Route::get('/missions/{id}', [MissionController::class, 'show'])->name('missions.show');

    Route::get('/user-missions', [UserMissionController::class, 'index'])->name('user-missions.index');
    Route::post('/user-missions', [UserMissionController::class, 'store'])->name('user-missions.store');
    Route::put('/user-missions/{id}', [UserMissionController::class, 'update'])->name('user-missions.update');

    Route::get('/ai-chat-sessions', [AiChatSessionController::class, 'index'])->name('ai-chat-sessions.index');
    Route::post('/ai-chat-sessions', [AiChatSessionController::class, 'store'])->name('ai-chat-sessions.store');
    Route::get('/ai-chat-sessions/{id}', [AiChatSessionController::class, 'show'])->name('ai-chat-sessions.show');
    Route::put('/ai-chat-sessions/{id}', [AiChatSessionController::class, 'update'])->name('ai-chat-sessions.update');
    Route::delete('/ai-chat-sessions/{id}', [AiChatSessionController::class, 'destroy'])->name('ai-chat-sessions.destroy');

    Route::get('/ai-chat-messages', [AiChatMessageController::class, 'index'])->name('ai-chat-messages.index');
    Route::post('/ai-chat-messages', [AiChatMessageController::class, 'store'])->name('ai-chat-messages.store');
    Route::get('/ai-chat-messages/{id}', [AiChatMessageController::class, 'show'])->name('ai-chat-messages.show');
    Route::delete('/ai-chat-messages/{id}', [AiChatMessageController::class, 'destroy'])->name('ai-chat-messages.destroy');
});