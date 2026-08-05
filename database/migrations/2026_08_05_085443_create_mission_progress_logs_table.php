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
        Schema::create('mission_progress_logs', function (Blueprint $table) {

            $table->id('progress_log_id');

            $table->foreignId('user_mission_id')
                ->constrained('user_missions', 'user_mission_id')
                ->cascadeOnDelete();

            $table->integer('progress');

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mission_progress_logs');
    }
};
