<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_waste_prices', function (Blueprint $table) {
            $table->id('mitra_price_id');

            $table->unsignedBigInteger('mitra_id');
            $table->foreign('mitra_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('waste_categories', 'category_id')
                ->cascadeOnDelete();

            $table->decimal('price_per_kg', 12, 2);

            $table->timestamps();

            $table->unique(['mitra_id', 'category_id'], 'mitra_category_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_waste_prices');
    }
};