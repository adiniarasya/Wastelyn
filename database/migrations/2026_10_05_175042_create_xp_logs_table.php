<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('xp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->cascadeOnDelete();
            $table->foreignId('bank_id')
                ->constrained('waste_banks', 'bank_id')
                ->cascadeOnDelete();
            $table->string('source', 50);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->integer('xp');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'bank_id', 'created_at']);
            $table->index(['user_id', 'source', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xp_logs');
    }
};