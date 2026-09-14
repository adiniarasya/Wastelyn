<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->foreignId('user_mission_id')
                ->nullable()
                ->after('user_id')
                ->constrained('user_missions', 'user_mission_id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->dropForeign(['user_mission_id']);
            $table->dropColumn('user_mission_id');
        });
    }
};
