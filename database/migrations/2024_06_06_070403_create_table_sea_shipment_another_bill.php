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
        Schema::create('tbl_sea_shipment_another_bill', function (Blueprint $table) {
            $table->id('id_sea_shipment_another_bill');
            $table->date('date')->nullable();
            $table->string('desc')->nullable();
            $table->string('charge')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_sea_shipment_another_bill');
    }
};
