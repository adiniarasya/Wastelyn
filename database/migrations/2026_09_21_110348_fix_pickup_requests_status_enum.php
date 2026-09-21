<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pickup_requests MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending'");

        DB::table('pickup_requests')->where('status', 'cancelled')->update(['status' => 'cancelled']);
        DB::table('pickup_requests')->where('status', 'rejected')->update(['status' => 'rejected']);
        DB::table('pickup_requests')->where('status', 'scheduled')->update(['status' => 'scheduled']);

        DB::statement("ALTER TABLE pickup_requests MODIFY COLUMN status ENUM(
            'pending',
            'accepted',
            'scheduled',
            'in_progress',
            'waiting_verification',
            'completed',
            'rejected',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pickup_requests MODIFY COLUMN status ENUM(
            'pending',
            'accepted',
            'scheduled',
            'completed',
            'rejected',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }
};