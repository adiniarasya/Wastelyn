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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id('submission_id');

            $table->foreignId('user_mission_id')
                ->constrained('user_missions', 'user_mission_id')
                ->cascadeOnDelete();

            $table->string('photo_path');
            $table->string('hash')->unique();
            $table->integer('detected_count')->default(0);
            $table->text('ai_response')->nullable();
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
