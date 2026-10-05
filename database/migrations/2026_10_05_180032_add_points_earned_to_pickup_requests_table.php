<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('pickup_requests', 'points_earned')) {
                $table->integer('points_earned')->default(0)->after('weight_kg');
            }
            if (!Schema::hasColumn('pickup_requests', 'co2_saved')) {
                $table->decimal('co2_saved', 8, 2)->default(0)->after('points_earned');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            if (Schema::hasColumn('pickup_requests', 'points_earned')) {
                $table->dropColumn('points_earned');
            }
            if (Schema::hasColumn('pickup_requests', 'co2_saved')) {
                $table->dropColumn('co2_saved');
            }
        });
    }
};