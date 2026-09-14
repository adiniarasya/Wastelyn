<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('pickup_requests', 'jenis_sampah')) {
                $table->string('jenis_sampah')->nullable();
            }
            
            if (!Schema::hasColumn('pickup_requests', 'estimasi_berat')) {
                $table->decimal('estimasi_berat', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('pickup_requests', 'berat_aktual')) {
                $table->decimal('berat_aktual', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('pickup_requests', 'total_harga')) {
                $table->decimal('total_harga', 12, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $columns = ['jenis_sampah', 'estimasi_berat', 'berat_aktual', 'total_harga'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pickup_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};