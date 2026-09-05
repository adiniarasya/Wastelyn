<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {

        DB::table('users')
            ->whereIn('role', ['admin', 'warga'])
            ->where('status', '!=', 'active')
            ->update(['status' => 'active']);

        DB::table('users')
            ->where('role', 'mitra')
            ->where('status', 'approved')
            ->update(['status' => 'pending']);
    }

    public function down(): void
    {

    }
};