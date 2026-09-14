<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('pickup_requests', 'waste_category_id')) {
                $table->foreignId('waste_category_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('waste_categories', 'category_id')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('pickup_requests', 'weight_kg')) {
                $table->decimal('weight_kg', 8, 2)->nullable()->after('waste_category_id');
            }
            if (!Schema::hasColumn('pickup_requests', 'points_earned')) {
                $table->integer('points_earned')->default(0)->after('weight_kg');
            }
            if (!Schema::hasColumn('pickup_requests', 'xp_earned')) {
                $table->integer('xp_earned')->default(0)->after('points_earned');
            }
            if (!Schema::hasColumn('pickup_requests', 'co2_saved')) {
                $table->decimal('co2_saved', 8, 3)->default(0)->after('xp_earned');
            }
            if (!Schema::hasColumn('pickup_requests', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('co2_saved');
            }
            if (!Schema::hasColumn('pickup_requests', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->dropForeign(['waste_category_id']);
            $table->dropColumn([
                'waste_category_id',
                'weight_kg',
                'points_earned',
                'xp_earned',
                'co2_saved',
                'verified_at',
                'rejection_reason',
            ]);
        });
    }
};