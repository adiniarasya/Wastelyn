<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('pickup_requests', 'courier_name')) {
                $table->string('courier_name')->nullable()->after('mitra_id');
            }
            if (!Schema::hasColumn('pickup_requests', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('verified_at');
            }
            if (!Schema::hasColumn('pickup_requests', 'received_at')) {
                $table->timestamp('received_at')->nullable()->after('started_at');
            }
            if (!Schema::hasColumn('pickup_requests', 'arrived_at')) {
                $table->timestamp('arrived_at')->nullable()->after('received_at');
            }
            if (!Schema::hasColumn('pickup_requests', 'price_per_kg')) {
                $table->decimal('price_per_kg', 12, 2)->nullable()->after('total_harga');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $columns = ['courier_name', 'started_at', 'received_at', 'arrived_at', 'price_per_kg'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('pickup_requests', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};