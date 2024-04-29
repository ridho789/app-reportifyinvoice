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
        Schema::table('tbl_shippers', function (Blueprint $table) {
            $table->unsignedBigInteger('id_history')->nullable()->after('id_shipper');
            $table->foreign('id_history')->references('id_history')->on('tbl_histories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_shippers', function (Blueprint $table) {
            $table->dropForeign(['id_history']);
            $table->dropColumn('id_history');
        });
    }
};
