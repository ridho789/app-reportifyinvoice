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
        Schema::table('tbl_sea_shipment_line', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sea_shipment')->after('id_sea_shipment_line');
            $table->foreign('id_sea_shipment')->references('id_sea_shipment')->on('tbl_sea_shipment');

            $table->unsignedBigInteger('id_unit')->nullable()->after('id_sea_shipment');
            $table->foreign('id_unit')->references('id_unit')->on('tbl_units');

            $table->unsignedBigInteger('id_state')->nullable()->after('id_unit');
            $table->foreign('id_state')->references('id_state')->on('tbl_states');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_sea_shipment_line', function (Blueprint $table) {
            $table->dropForeign(['id_sea_shipment']);
            $table->dropColumn('id_sea_shipment');

            $table->dropForeign(['id_unit']);
            $table->dropColumn('id_unit');

            $table->dropForeign(['id_state']);
            $table->dropColumn('id_state');
        });
    }
};
