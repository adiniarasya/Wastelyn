<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_xp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->cascadeOnDelete();
            $table->foreignId('bank_id')
                ->constrained('waste_banks', 'bank_id')
                ->cascadeOnDelete();
            $table->integer('xp')->default(0);
            $table->integer('level')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'bank_id']);
            $table->index(['bank_id', 'xp']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_xp');
    }
};