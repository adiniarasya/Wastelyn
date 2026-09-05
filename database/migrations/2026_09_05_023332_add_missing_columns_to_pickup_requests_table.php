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
        Schema::table('pickup_requests', function (Blueprint $table) {
            // Cek apakah kolom ada sebelum menambahkan
            if (!Schema::hasColumn('pickup_requests', 'estimasi_berat')) {
                $table->decimal('estimasi_berat', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('pickup_requests', 'berat_aktual')) {
                $table->decimal('berat_aktual', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('pickup_requests', 'total_harga')) {
                $table->decimal('total_harga', 12, 2)->nullable();
            }
            
            if (!Schema::hasColumn('pickup_requests', 'alamat')) {
                $table->text('alamat')->nullable();
            }
            
            if (!Schema::hasColumn('pickup_requests', 'jadwal_penjemputan')) {
                $table->string('jadwal_penjemputan')->nullable();
            }
            
            if (!Schema::hasColumn('pickup_requests', 'status')) {
                $table->enum('status', ['pending', 'accepted', 'completed', 'cancelled'])->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $columns = ['estimasi_berat', 'berat_aktual', 'total_harga', 'alamat', 'jadwal_penjemputan', 'status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pickup_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};