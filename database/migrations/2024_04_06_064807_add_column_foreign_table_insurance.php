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
        Schema::table('tbl_insurances', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sea_shipment_line')->unique()->after('id_insurance');
            $table->foreign('id_sea_shipment_line')->references('id_sea_shipment_line')->on('tbl_sea_shipment_line');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_insurances', function (Blueprint $table) {
            $table->dropForeign(['id_sea_shipment_line']);
            $table->dropColumn('id_sea_shipment_line');
        });
    }
};
