<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reward_redemptions', function (Blueprint $table) {
            $table->string('voucher_code')->nullable()->after('status');
            $table->text('delivery_note')->nullable()->after('voucher_code');
            $table->timestamp('delivered_at')->nullable()->after('processed_at');
        });
    }

    public function down(): void
    {
        Schema::table('reward_redemptions', function (Blueprint $table) {
            $table->dropColumn(['voucher_code', 'delivery_note', 'delivered_at']);
        });
    }
};