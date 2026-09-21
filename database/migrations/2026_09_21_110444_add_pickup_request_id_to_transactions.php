<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'pickup_request_id')) {
                $table->foreignId('pickup_request_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('pickup_requests', 'pickup_request_id')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('transactions', 'mitra_id')) {
                $table->unsignedBigInteger('mitra_id')->nullable()->after('pickup_request_id');
            }
            if (!Schema::hasColumn('transactions', 'xp_earned')) {
                $table->integer('xp_earned')->default(0)->after('points');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'pickup_request_id')) {
                $table->dropForeign(['pickup_request_id']);
                $table->dropColumn('pickup_request_id');
            }
            if (Schema::hasColumn('transactions', 'mitra_id')) {
                $table->dropColumn('mitra_id');
            }
            if (Schema::hasColumn('transactions', 'xp_earned')) {
                $table->dropColumn('xp_earned');
            }
        });
    }
};