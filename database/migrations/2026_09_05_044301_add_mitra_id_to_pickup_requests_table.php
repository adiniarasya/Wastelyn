<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pickup_requests', function (Blueprint $table) {
        $table->unsignedBigInteger('mitra_id')->nullable()->after('bank_id');
        $table->foreign('mitra_id')->references('user_id')->on('users')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('pickup_requests', function (Blueprint $table) {
        $table->dropForeign(['mitra_id']);
        $table->dropColumn('mitra_id');
    });
}
};
