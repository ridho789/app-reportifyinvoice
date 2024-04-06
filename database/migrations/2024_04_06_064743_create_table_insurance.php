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
        Schema::create('tbl_insurances', function (Blueprint $table) {
            $table->id('id_insurance');
            $table->string('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->string('description')->nullable();
            $table->string('currency')->nullable();
            $table->string('original_price')->nullable();
            $table->string('exchange_rate')->nullable();
            $table->string('idr')->nullable();
            $table->string('premi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_insurances');
    }
};
