<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_missions', function (Blueprint $table) {
            $table->string('unique_code')->nullable()->unique()->after('mission_id');
            $table->timestamp('pickup_requested_at')->nullable()->after('completed_at');
            $table->timestamp('picked_up_at')->nullable()->after('pickup_requested_at');
        });

        DB::statement("ALTER TABLE user_missions MODIFY COLUMN status ENUM('ongoing', 'ready_pickup', 'picked_up', 'completed') NOT NULL DEFAULT 'ongoing'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE user_missions MODIFY COLUMN status ENUM('ongoing', 'completed') NOT NULL DEFAULT 'ongoing'");

        Schema::table('user_missions', function (Blueprint $table) {
            $table->dropColumn(['unique_code', 'pickup_requested_at', 'picked_up_at']);
        });
    }
};
