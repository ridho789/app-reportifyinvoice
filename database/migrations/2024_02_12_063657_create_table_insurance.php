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
        Schema::create('tbl_insurance', function (Blueprint $table) {
            $table->id('id_insurance');
            $table->string('quantity');
            $table->string('unit');
            $table->string('description')->nullable();
            $table->string('currency');
            $table->string('original_price');
            $table->string('exchange_rate');
            $table->string('idr');
            $table->string('premi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_insurance');
    }
};
