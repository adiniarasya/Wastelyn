<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waste_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('waste_categories', 'reward_per_kg')) {
                $table->integer('reward_per_kg')->default(1000)->after('name');
            }
            if (!Schema::hasColumn('waste_categories', 'co2_saved_per_kg')) {
                $table->decimal('co2_saved_per_kg', 8, 3)->default(0.5)->after('reward_per_kg');
            }
        });
    }

    public function down(): void
    {
        Schema::table('waste_categories', function (Blueprint $table) {
            $table->dropColumn(['reward_per_kg', 'co2_saved_per_kg']);
        });
    }
};