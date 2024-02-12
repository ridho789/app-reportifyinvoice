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
        Schema::table('tbl_insurance', function (Blueprint $table) {
            $table->unsignedBigInteger('id_item')->after('id_insurance');
            $table->foreign('id_item')->references('id_item')->on('tbl_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_insurance', function (Blueprint $table) {
            $table->dropForeign(['id_item']);
            $table->dropColumn('id_item');
        });
    }
};
