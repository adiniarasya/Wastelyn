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
        Schema::create('pickup_items', function (Blueprint $table) {
            $table->id('pickup_item_id');

            $table->foreignId('pickup_request_id')
                ->constrained('pickup_requests','pickup_request_id')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('waste_categories','category_id')
                ->cascadeOnDelete();

            $table->decimal('weight',8,2);
            $table->decimal('price',10,2);
            $table->integer('points');
            $table->decimal('subtotal',10,2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_items');
    }
};
