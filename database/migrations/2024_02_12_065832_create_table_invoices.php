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
        Schema::create('tbl_invoices', function (Blueprint $table) {
            $table->id('id_invoice');
            $table->string('weight');
            $table->string('cbm');
            $table->string('price');
            $table->string('amount');
            $table->date('inv_date');
            $table->string('spelled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_invoices');
    }
};
