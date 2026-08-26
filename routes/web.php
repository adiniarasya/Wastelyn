<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WasteBankController;
use App\Http\Controllers\WasteCategoryController;
use App\Http\Controllers\WasteBankStaffController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RewardRedemptionController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\UserMissionController;
use App\Http\Controllers\MissionProgressLogController;
use App\Http\Controllers\PickupRequestController;
use App\Http\Controllers\PickupItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AiChatSessionController;
use App\Http\Controllers\AiChatMessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

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

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ADMIN 
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/waste-banks', [WasteBankController::class, 'index'])->name('waste-banks.index');
        Route::get('/waste-banks/create', [WasteBankController::class, 'create'])->name('waste-banks.create');
        Route::post('/waste-banks', [WasteBankController::class, 'store'])->name('waste-banks.store');
        Route::get('/waste-banks/{id}', [WasteBankController::class, 'show'])->name('waste-banks.show');
        Route::get('/waste-banks/{id}/edit', [WasteBankController::class, 'edit'])->name('waste-banks.edit');
        Route::put('/waste-banks/{id}', [WasteBankController::class, 'update'])->name('waste-banks.update');
        Route::delete('/waste-banks/{id}', [WasteBankController::class, 'destroy'])->name('waste-banks.destroy');

        Route::get('/waste-categories', [WasteCategoryController::class, 'index'])->name('waste-categories.index');
        Route::get('/waste-categories/create', [WasteCategoryController::class, 'create'])->name('waste-categories.create');
        Route::post('/waste-categories', [WasteCategoryController::class, 'store'])->name('waste-categories.store');
        Route::get('/waste-categories/{id}', [WasteCategoryController::class, 'show'])->name('waste-categories.show');
        Route::get('/waste-categories/{id}/edit', [WasteCategoryController::class, 'edit'])->name('waste-categories.edit');
        Route::put('/waste-categories/{id}', [WasteCategoryController::class, 'update'])->name('waste-categories.update');
        Route::delete('/waste-categories/{id}', [WasteCategoryController::class, 'destroy'])->name('waste-categories.destroy');

        Route::get('/waste-bank-staff', [WasteBankStaffController::class, 'index'])->name('waste-bank-staff.index');
        Route::get('/waste-bank-staff/create', [WasteBankStaffController::class, 'create'])->name('waste-bank-staff.create');
        Route::post('/waste-bank-staff', [WasteBankStaffController::class, 'store'])->name('waste-bank-staff.store');
        Route::get('/waste-bank-staff/{id}', [WasteBankStaffController::class, 'show'])->name('waste-bank-staff.show');
        Route::get('/waste-bank-staff/{id}/edit', [WasteBankStaffController::class, 'edit'])->name('waste-bank-staff.edit');
        Route::put('/waste-bank-staff/{id}', [WasteBankStaffController::class, 'update'])->name('waste-bank-staff.update');
        Route::delete('/waste-bank-staff/{id}', [WasteBankStaffController::class, 'destroy'])->name('waste-bank-staff.destroy');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
        Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
        Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('transactions.status');

        Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
        Route::get('/rewards/create', [RewardController::class, 'create'])->name('rewards.create');
        Route::post('/rewards', [RewardController::class, 'store'])->name('rewards.store');
        Route::get('/rewards/{id}', [RewardController::class, 'show'])->name('rewards.show');
        Route::get('/rewards/{id}/edit', [RewardController::class, 'edit'])->name('rewards.edit');
        Route::put('/rewards/{id}', [RewardController::class, 'update'])->name('rewards.update');
        Route::delete('/rewards/{id}', [RewardController::class, 'destroy'])->name('rewards.destroy');

        Route::get('/reward-redemptions', [RewardRedemptionController::class, 'index'])->name('reward-redemptions.index');
        Route::get('/reward-redemptions/create', [RewardRedemptionController::class, 'create'])->name('reward-redemptions.create');
        Route::post('/reward-redemptions', [RewardRedemptionController::class, 'store'])->name('reward-redemptions.store');
        Route::get('/reward-redemptions/{id}', [RewardRedemptionController::class, 'show'])->name('reward-redemptions.show');
        Route::get('/reward-redemptions/{id}/edit', [RewardRedemptionController::class, 'edit'])->name('reward-redemptions.edit');
        Route::put('/reward-redemptions/{id}', [RewardRedemptionController::class, 'update'])->name('reward-redemptions.update');
        Route::delete('/reward-redemptions/{id}', [RewardRedemptionController::class, 'destroy'])->name('reward-redemptions.destroy');
        Route::put('/reward-redemptions/{id}/approve', [RewardRedemptionController::class, 'approve'])->name('reward-redemptions.approve');
        Route::put('/reward-redemptions/{id}/reject', [RewardRedemptionController::class, 'reject'])->name('reward-redemptions.reject');

        Route::get('/missions', [MissionController::class, 'index'])->name('missions.index');
        Route::get('/missions/create', [MissionController::class, 'create'])->name('missions.create');
        Route::post('/missions', [MissionController::class, 'store'])->name('missions.store');
        Route::get('/missions/{id}', [MissionController::class, 'show'])->name('missions.show');
        Route::get('/missions/{id}/edit', [MissionController::class, 'edit'])->name('missions.edit');
        Route::put('/missions/{id}', [MissionController::class, 'update'])->name('missions.update');
        Route::delete('/missions/{id}', [MissionController::class, 'destroy'])->name('missions.destroy');

        Route::get('/user-missions', [UserMissionController::class, 'index'])->name('user-missions.index');
        Route::get('/user-missions/create', [UserMissionController::class, 'create'])->name('user-missions.create');
        Route::post('/user-missions', [UserMissionController::class, 'store'])->name('user-missions.store');
        Route::get('/user-missions/{id}', [UserMissionController::class, 'show'])->name('user-missions.show');
        Route::get('/user-missions/{id}/edit', [UserMissionController::class, 'edit'])->name('user-missions.edit');
        Route::put('/user-missions/{id}', [UserMissionController::class, 'update'])->name('user-missions.update');
        Route::delete('/user-missions/{id}', [UserMissionController::class, 'destroy'])->name('user-missions.destroy');

        Route::get('/mission-progress-logs', [MissionProgressLogController::class, 'index'])->name('mission-progress-logs.index');
        Route::get('/mission-progress-logs/create', [MissionProgressLogController::class, 'create'])->name('mission-progress-logs.create');
        Route::post('/mission-progress-logs', [MissionProgressLogController::class, 'store'])->name('mission-progress-logs.store');
        Route::get('/mission-progress-logs/{id}', [MissionProgressLogController::class, 'show'])->name('mission-progress-logs.show');
        Route::get('/mission-progress-logs/{id}/edit', [MissionProgressLogController::class, 'edit'])->name('mission-progress-logs.edit');
        Route::put('/mission-progress-logs/{id}', [MissionProgressLogController::class, 'update'])->name('mission-progress-logs.update');
        Route::delete('/mission-progress-logs/{id}', [MissionProgressLogController::class, 'destroy'])->name('mission-progress-logs.destroy');

        Route::get('/pickup-requests', [PickupRequestController::class, 'index'])->name('pickup-requests.index');
        Route::get('/pickup-requests/create', [PickupRequestController::class, 'create'])->name('pickup-requests.create');
        Route::post('/pickup-requests', [PickupRequestController::class, 'store'])->name('pickup-requests.store');
        Route::get('/pickup-requests/{id}', [PickupRequestController::class, 'show'])->name('pickup-requests.show');
        Route::get('/pickup-requests/{id}/edit', [PickupRequestController::class, 'edit'])->name('pickup-requests.edit');
        Route::put('/pickup-requests/{id}', [PickupRequestController::class, 'update'])->name('pickup-requests.update');
        Route::delete('/pickup-requests/{id}', [PickupRequestController::class, 'destroy'])->name('pickup-requests.destroy');
        Route::put('/pickup-requests/{id}/assign', [PickupRequestController::class, 'assign'])->name('pickup-requests.assign');
        Route::put('/pickup-requests/{id}/status', [PickupRequestController::class, 'updateStatus'])->name('pickup-requests.status');

        Route::get('/pickup-items', [PickupItemController::class, 'index'])->name('pickup-items.index');
        Route::get('/pickup-items/create', [PickupItemController::class, 'create'])->name('pickup-items.create');
        Route::post('/pickup-items', [PickupItemController::class, 'store'])->name('pickup-items.store');
        Route::get('/pickup-items/{id}', [PickupItemController::class, 'show'])->name('pickup-items.show');
        Route::get('/pickup-items/{id}/edit', [PickupItemController::class, 'edit'])->name('pickup-items.edit');
        Route::put('/pickup-items/{id}', [PickupItemController::class, 'update'])->name('pickup-items.update');
        Route::delete('/pickup-items/{id}', [PickupItemController::class, 'destroy'])->name('pickup-items.destroy');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
        Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
        Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
        Route::get('/notifications/{id}/edit', [NotificationController::class, 'edit'])->name('notifications.edit');
        Route::put('/notifications/{id}', [NotificationController::class, 'update'])->name('notifications.update');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::post('/notifications/broadcast', [NotificationController::class, 'broadcast'])->name('notifications.broadcast');
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    });

    //MITRA
    Route::middleware('role:mitra')->prefix('mitra')->name('mitra.')->group(function () {
        Route::get('/dashboard', [MitraController::class, 'dashboard'])->name('dashboard');

        Route::get('/pickup-requests', [PickupRequestController::class, 'index'])->name('pickup-requests.index');
        Route::get('/pickup-requests/{id}', [PickupRequestController::class, 'show'])->name('pickup-requests.show');
        Route::put('/pickup-requests/{id}/status', [PickupRequestController::class, 'updateStatus'])->name('pickup-requests.status');

        Route::get('/pickup-items', [PickupItemController::class, 'index'])->name('pickup-items.index');
        Route::post('/pickup-items', [PickupItemController::class, 'store'])->name('pickup-items.store');
        Route::put('/pickup-items/{id}', [PickupItemController::class, 'update'])->name('pickup-items.update');
        Route::delete('/pickup-items/{id}', [PickupItemController::class, 'destroy'])->name('pickup-items.destroy');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('transactions.status');

        Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
        Route::get('/rewards/{id}', [RewardController::class, 'show'])->name('rewards.show');

        Route::get('/reward-redemptions', [RewardRedemptionController::class, 'index'])->name('reward-redemptions.index');
        Route::put('/reward-redemptions/{id}/approve', [RewardRedemptionController::class, 'approve'])->name('reward-redemptions.approve');
        Route::put('/reward-redemptions/{id}/reject', [RewardRedemptionController::class, 'reject'])->name('reward-redemptions.reject');

        Route::get('/missions', [MissionController::class, 'index'])->name('missions.index');
        Route::get('/missions/{id}', [MissionController::class, 'show'])->name('missions.show');

        Route::get('/user-missions', [UserMissionController::class, 'index'])->name('user-missions.index');
        Route::put('/user-missions/{id}/status', [UserMissionController::class, 'updateStatus'])->name('user-missions.status');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    });

    //WARGA
    Route::middleware('role:warga')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

        Route::get('/waste-banks', [WasteBankController::class, 'index'])->name('waste-banks.index');
        Route::get('/waste-banks/{id}', [WasteBankController::class, 'show'])->name('waste-banks.show');

        Route::get('/waste-categories', [WasteCategoryController::class, 'index'])->name('waste-categories.index');

        Route::get('/pickup-requests', [PickupRequestController::class, 'index'])->name('pickup-requests.index');
        Route::get('/pickup-requests/create', [PickupRequestController::class, 'create'])->name('pickup-requests.create');
        Route::post('/pickup-requests', [PickupRequestController::class, 'store'])->name('pickup-requests.store');
        Route::get('/pickup-requests/{id}', [PickupRequestController::class, 'show'])->name('pickup-requests.show');
        Route::delete('/pickup-requests/{id}', [PickupRequestController::class, 'destroy'])->name('pickup-requests.destroy');

        Route::get('/pickup-items', [PickupItemController::class, 'index'])->name('pickup-items.index');
        Route::post('/pickup-items', [PickupItemController::class, 'store'])->name('pickup-items.store');
        Route::delete('/pickup-items/{id}', [PickupItemController::class, 'destroy'])->name('pickup-items.destroy');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');

        Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
        Route::get('/rewards/{id}', [RewardController::class, 'show'])->name('rewards.show');

        Route::get('/reward-redemptions', [RewardRedemptionController::class, 'index'])->name('reward-redemptions.index');
        Route::get('/reward-redemptions/create', [RewardRedemptionController::class, 'create'])->name('reward-redemptions.create');
        Route::post('/reward-redemptions', [RewardRedemptionController::class, 'store'])->name('reward-redemptions.store');

        Route::get('/missions', [MissionController::class, 'index'])->name('missions.index');
        Route::get('/missions/{id}', [MissionController::class, 'show'])->name('missions.show');

        Route::get('/user-missions', [UserMissionController::class, 'index'])->name('user-missions.index');
        Route::post('/user-missions', [UserMissionController::class, 'store'])->name('user-missions.store');
        Route::put('/user-missions/{id}', [UserMissionController::class, 'update'])->name('user-missions.update');

        Route::get('/mission-progress-logs', [MissionProgressLogController::class, 'index'])->name('mission-progress-logs.index');
        Route::post('/mission-progress-logs', [MissionProgressLogController::class, 'store'])->name('mission-progress-logs.store');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

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
});
