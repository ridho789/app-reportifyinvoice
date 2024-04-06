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
        Schema::table('tbl_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('id_item')->after('id_invoice');
            $table->foreign('id_item')->references('id_item')->on('tbl_items');

            $table->unsignedBigInteger('id_price')->after('id_item');
            $table->foreign('id_price')->references('id_price')->on('tbl_prices');

            $table->unsignedBigInteger('id_cas')->after('id_price');
            $table->foreign('id_cas')->references('id_cas')->on('tbl_cas_items');

            $table->unsignedBigInteger('id_insurance')->after('id_cas');
            $table->foreign('id_insurance')->references('id_insurance')->on('tbl_insurances');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_invoices', function (Blueprint $table) {
            $table->dropForeign(['id_item']);
            $table->dropColumn('id_item');

            $table->dropForeign(['id_price']);
            $table->dropColumn('id_price');

            $table->dropForeign(['id_cas']);
            $table->dropColumn('id_cas');

            $table->dropForeign(['id_insurance']);
            $table->dropColumn('id_insurance');
        });
    }
};
