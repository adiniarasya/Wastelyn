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
        Schema::table('mission_progress_logs', function (Blueprint $table) {
            $table->foreignId('submission_id')
                ->nullable()
                ->after('user_mission_id')
                ->constrained('submissions', 'submission_id')
                ->nullOnDelete();

            $table->integer('progress_increment')->default(0)->after('progress');
            $table->integer('progress_after')->default(0)->after('progress_increment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_progress_logs', function (Blueprint $table) {
            $table->dropForeign(['submission_id']);
            $table->dropColumn(['submission_id', 'progress_increment', 'progress_after']);
        });
    }
};
