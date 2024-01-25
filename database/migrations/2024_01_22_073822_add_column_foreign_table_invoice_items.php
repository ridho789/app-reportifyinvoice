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
        Schema::table('tbl_invoice_items', function (Blueprint $table) {
            $table->unsignedBigInteger('id_invoice')->after('id_invoice_item');
            $table->foreign('id_invoice')->references('id_invoice')->on('tbl_invoices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_invoice_items', function (Blueprint $table) {
            $table->dropForeign(['id_invoice']);
            $table->dropColumn('id_invoice');
        });
    }
};
