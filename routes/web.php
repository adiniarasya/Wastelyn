<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WasteBankController;
use App\Http\Controllers\WasteCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\UserMissionController;
use App\Http\Controllers\PickupRequestController;
use App\Http\Controllers\AiChatSessionController;
use App\Http\Controllers\AiChatMessageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
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

//admin
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

    // Waste Category Management (Admin)
    Route::get('/waste-categories', [WasteCategoryController::class, 'index'])->name('waste-categories.index');
    Route::get('/waste-categories/create', [WasteCategoryController::class, 'create'])->name('waste-categories.create');
    Route::post('/waste-categories', [WasteCategoryController::class, 'store'])->name('waste-categories.store');
    Route::get('/waste-categories/{wasteCategory}', [WasteCategoryController::class, 'show'])->name('waste-categories.show');
    Route::get('/waste-categories/{wasteCategory}/edit', [WasteCategoryController::class, 'edit'])->name('waste-categories.edit');
    Route::put('/waste-categories/{wasteCategory}', [WasteCategoryController::class, 'update'])->name('waste-categories.update');
    Route::delete('/waste-categories/{wasteCategory}', [WasteCategoryController::class, 'destroy'])->name('waste-categories.destroy');

    // Mission Management (Admin)
    Route::get('/missions', [MissionController::class, 'index'])->name('missions.index');
    Route::get('/missions/create', [MissionController::class, 'create'])->name('missions.create');
    Route::post('/missions', [MissionController::class, 'store'])->name('missions.store');
    Route::get('/missions/{mission}', [MissionController::class, 'show'])->name('missions.show');
    Route::get('/missions/{mission}/edit', [MissionController::class, 'edit'])->name('missions.edit');
    Route::put('/missions/{mission}', [MissionController::class, 'update'])->name('missions.update');
    Route::delete('/missions/{mission}', [MissionController::class, 'destroy'])->name('missions.destroy');

    // Reward Management (Admin)
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

//mitra
Route::middleware(['auth', 'role:mitra'])->prefix('mitra')->name('mitra.')->group(function () {

    Route::get('/dashboard', [MitraController::class, 'dashboard'])->name('dashboard');

    Route::get('/laporan', [MitraController::class, 'laporan'])->name('laporan');
    // Pickup Requests
    Route::get('/pickup-requests', [PickupRequestController::class, 'mitraIndex'])->name('pickup-requests.index');
        Route::get('/pickup-requests/export-pdf', [PickupRequestController::class, 'exportSetoranPdf'])->name('pickup-requests.export-pdf');
    Route::get('/pickup-requests/{pickupRequest}', [PickupRequestController::class, 'mitraShow'])->name('pickup-requests.show');
    Route::put('/pickup-requests/{pickupRequest}/status', [PickupRequestController::class, 'updateStatus'])->name('pickup-requests.status');
    // Alur Pickup/DropOff (baru)
    Route::post('/pickup-requests/{id}/take', [\App\Http\Controllers\PickupFlowController::class, 'take'])->name('pickup-requests.take');
    Route::post('/pickup-requests/{id}/start', [\App\Http\Controllers\PickupFlowController::class, 'start'])->name('pickup-requests.start');
    Route::post('/pickup-requests/{id}/receive', [\App\Http\Controllers\PickupFlowController::class, 'receive'])->name('pickup-requests.receive');
    Route::post('/pickup-requests/{id}/verify', [\App\Http\Controllers\PickupFlowController::class, 'verify'])->name('pickup-requests.verify');
    Route::post('/pickup-requests/{id}/reject', [\App\Http\Controllers\PickupFlowController::class, 'reject'])->name('pickup-requests.reject');
    // Transactions

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export-pdf', [TransactionController::class, 'exportPdf'])->name('transactions.export-pdf');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/statistics', [MitraController::class, 'statistics'])->name('statistics');
    Route::get('/profile', [MitraController::class, 'profile'])->name('profile');
    Route::put('/profile', [MitraController::class, 'updateProfile'])->name('profile.update');
        // Kelola Harga Sampah
    Route::get('/harga-sampah', [\App\Http\Controllers\MitraWastePriceController::class, 'index'])->name('harga-sampah.index');
    Route::put('/harga-sampah/{categoryId}', [\App\Http\Controllers\MitraWastePriceController::class, 'update'])->name('harga-sampah.update');
    Route::delete('/harga-sampah/{categoryId}', [\App\Http\Controllers\MitraWastePriceController::class, 'destroy'])->name('harga-sampah.destroy');
    //mision reward
    Route::get('/missions', [MissionController::class, 'mitraIndex'])->name('missions.index');
    Route::get('/missions/create', [MissionController::class, 'mitraCreate'])->name('missions.create');
    Route::post('/missions', [MissionController::class, 'mitraStore'])->name('missions.store');
    Route::get('/missions/{mission}', [MissionController::class, 'mitraShow'])->name('missions.show');
    Route::get('/missions/{mission}/edit', [MissionController::class, 'mitraEdit'])->name('missions.edit');
    Route::put('/missions/{mission}', [MissionController::class, 'mitraUpdate'])->name('missions.update');
    Route::delete('/missions/{mission}', [MissionController::class, 'mitraDestroy'])->name('missions.destroy');
        //rewards
    Route::get('/rewards', [RewardController::class, 'mitraIndex'])->name('rewards.index');
    Route::get('/rewards/redemptions', [RewardController::class, 'mitraRedemptions'])->name('rewards.redemptions');
    Route::put('/rewards/redemptions/{id}', [RewardController::class, 'mitraUpdateRedemption'])->name('rewards.redemptions.update');
    Route::get('/rewards/create', [RewardController::class, 'mitraCreate'])->name('rewards.create');
    Route::post('/rewards', [RewardController::class, 'mitraStore'])->name('rewards.store');
    Route::get('/rewards/{reward}', [RewardController::class, 'mitraShow'])->name('rewards.show');
    Route::get('/rewards/{reward}/edit', [RewardController::class, 'mitraEdit'])->name('rewards.edit');
    Route::put('/rewards/{reward}', [RewardController::class, 'mitraUpdate'])->name('rewards.update');
    Route::delete('/rewards/{reward}', [RewardController::class, 'mitraDestroy'])->name('rewards.destroy');
    //rewards
    Route::get('/rewards', [RewardController::class, 'mitraIndex'])->name('rewards.index');
    Route::get('/rewards/create', [RewardController::class, 'mitraCreate'])->name('rewards.create');
    Route::post('/rewards', [RewardController::class, 'mitraStore'])->name('rewards.store');
    Route::get('/rewards/{reward}', [RewardController::class, 'mitraShow'])->name('rewards.show');
    Route::get('/rewards/{reward}/edit', [RewardController::class, 'mitraEdit'])->name('rewards.edit');
    Route::put('/rewards/{reward}', [RewardController::class, 'mitraUpdate'])->name('rewards.update');
    Route::delete('/rewards/{reward}', [RewardController::class, 'mitraDestroy'])->name('rewards.destroy');

        // Notifikasi Mitra
    Route::get('/notifications', [\App\Http\Controllers\MitraNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\MitraNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\MitraNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\MitraNotificationController::class, 'destroy'])->name('notifications.destroy');
});


//user
Route::middleware('role:warga')->prefix('user')->name('user.')->group(function () {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::get('/waste-banks', [WasteBankController::class, 'userIndex'])->name('waste-banks.index');
    Route::get('/waste-banks/{wasteBank}', [WasteBankController::class, 'userShow'])->name('waste-banks.show');

    Route::get('/pickup-requests', [PickupRequestController::class, 'userIndex'])->name('pickup-requests.index');
    Route::get('/pickup-requests/create', [PickupRequestController::class, 'userCreate'])->name('pickup-requests.create');
    Route::post('/pickup-requests', [PickupRequestController::class, 'userStore'])->name('pickup-requests.store');
    Route::get('/pickup-requests/{pickupRequest}', [PickupRequestController::class, 'userShow'])->name('pickup-requests.show');
    Route::delete('/pickup-requests/{pickupRequest}', [PickupRequestController::class, 'userDestroy'])->name('pickup-requests.destroy');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    //rewards (warga)
    Route::get('/rewards', [RewardController::class, 'userIndex'])->name('rewards.index');
    Route::get('/rewards/{id}', [RewardController::class, 'userShow'])->name('rewards.show');
    Route::post('/rewards/{id}/redeem', [RewardController::class, 'userRedeem'])->name('rewards.redeem');
    
    Route::get('/user-missions', [UserMissionController::class, 'index'])->name('user-missions.index');
    Route::post('/user-missions', [UserMissionController::class, 'store'])->name('user-missions.store');
    Route::put('/user-missions/{id}', [UserMissionController::class, 'update'])->name('user-missions.update');
    Route::get('/user-missions/{mission}', [UserMissionController::class, 'show'])->name('user-missions.show');

    Route::post('/user-missions/{userMission}/submissions', [SubmissionController::class, 'store'])->name('submissions.store');

    Route::get('/ai-chat-sessions', [AiChatSessionController::class, 'index'])->name('ai-chat-sessions.index');
    Route::post('/ai-chat-sessions', [AiChatSessionController::class, 'store'])->name('ai-chat-sessions.store');
    Route::get('/ai-chat-sessions/{id}', [AiChatSessionController::class, 'show'])->name('ai-chat-sessions.show');
    Route::put('/ai-chat-sessions/{id}', [AiChatSessionController::class, 'update'])->name('ai-chat-sessions.update');
    Route::delete('/ai-chat-sessions/{id}', [AiChatSessionController::class, 'destroy'])->name('ai-chat-sessions.destroy');

    Route::post('/ai-chat-sessions/{sessionId}/messages', [AiChatMessageController::class, 'store'])
        ->middleware('throttle:20,1')
        ->name('ai-chat-messages.store');

    Route::delete('/ai-chat-sessions/{sessionId}/messages/{messageId}', [AiChatMessageController::class, 'destroy'])
        ->name('ai-chat-messages.destroy');

        Route::post('/pickup-requests/{id}/arrive', [\App\Http\Controllers\DropOffController::class, 'arrive'])->name('pickup-requests.arrive');

});