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
        Schema::table('missions', function (Blueprint $table) {
            $table->foreignId('bank_id')
                ->nullable()
                ->after('mission_id')
                ->constrained('waste_banks', 'bank_id')
                ->cascadeOnDelete();

            $table->enum('type', ['quantitative', 'qualitative'])
                ->default('quantitative')
                ->after('target');

            $table->string('unit')->nullable()->after('type');

            $table->text('ai_prompt')->nullable()->after('unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
            $table->dropColumn(['bank_id', 'type', 'unit', 'ai_prompt']);
        });
    }
};
