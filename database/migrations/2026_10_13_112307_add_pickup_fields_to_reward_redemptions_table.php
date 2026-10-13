<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reward_redemptions', function (Blueprint $table) {
            $table->string('pickup_code')->nullable()->after('voucher_code');
            $table->date('pickup_date')->nullable()->after('pickup_code');
            $table->timestamp('pickup_expires_at')->nullable()->after('pickup_date');
            $table->timestamp('picked_up_at')->nullable()->after('pickup_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('reward_redemptions', function (Blueprint $table) {
            $table->dropColumn(['pickup_code', 'pickup_date', 'pickup_expires_at', 'picked_up_at']);
        });
    }
};