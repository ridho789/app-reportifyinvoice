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
        Schema::create('tbl_items', function (Blueprint $table) {
            $table->id('id_item');
            $table->date('date')->nullable();
            $table->string('no_bl')->nullable();
            $table->string('no_inv')->nullable();
            $table->string('marking');
            $table->string('pallet_code');
            $table->string('quantity');
            $table->string('length');
            $table->string('width');
            $table->string('height');
            $table->string('weight');
            $table->string('cbm1');
            $table->string('cbm2');
            $table->boolean('split')->nullable();
            $table->string('lts_code')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_items');
    }
};
