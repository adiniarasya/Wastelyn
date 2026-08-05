<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id('pickup_request_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreignId('bank_id')
                ->constrained('waste_banks', 'bank_id')
                ->cascadeOnDelete();

            $table->enum('pickup_method', ['pickup', 'dropoff']);
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->text('address');
            $table->text('notes')->nullable();

            $table->enum('status', [
                'pending',
                'accepted',
                'scheduled',
                'completed',
                'rejected',
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};
