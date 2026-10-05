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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('waste_bank_id')
                ->nullable()
                ->after('role')
                ->constrained('waste_banks', 'bank_id')
                ->nullOnDelete();

            $table->boolean('onboarding_completed')
                ->default(false)
                ->after('waste_bank_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['waste_bank_id']);
            $table->dropColumn(['waste_bank_id', 'onboarding_completed']);
        });
    }
};
