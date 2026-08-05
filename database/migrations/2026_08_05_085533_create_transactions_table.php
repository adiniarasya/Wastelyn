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
        Schema::create('transactions', function (Blueprint $table) {

            $table->id('transaction_id');

            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->cascadeOnDelete();

            $table->foreignId('pickup_request_id')
                ->nullable()
                ->constrained('pickup_requests', 'pickup_request_id')
                ->nullOnDelete();

            $table->foreignId('redemption_id')
                ->nullable()
                ->constrained('reward_redemptions', 'redemption_id')
                ->nullOnDelete();

            $table->enum('type', [
                'earn',
                'redeem'
            ]);

            $table->integer('points');

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
