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
        Schema::table('tbl_shippings', function (Blueprint $table) {
            $table->unsignedBigInteger('id_ship')->after('id_shipping');
            $table->foreign('id_ship')->references('id_ship')->on('tbl_ships');

            $table->unsignedBigInteger('id_shipping_type')->after('id_ship');
            $table->foreign('id_shipping_type')->references('id_shipping_type')->on('tbl_shipping_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_shippings', function (Blueprint $table) {
            $table->dropForeign(['id_ship']);
            $table->dropColumn('id_ship');

            $table->dropForeign(['id_shipping_type']);
            $table->dropColumn('id_shipping_type');
        });
    }
};
