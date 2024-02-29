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
        Schema::create('table_sea_shipment_line', function (Blueprint $table) {
            $table->id('id_sea_shipment_line');
            $table->date('date');
            $table->string('code');
            $table->string('marking');
            $table->string('qty_pkgs');
            $table->string('qty_loose');
            $table->string('weight');
            $table->string('dimension_p');
            $table->string('dimension_l');
            $table->string('dimension_t');
            $table->string('tot_cbm_1');
            $table->string('tot_cbm_2');
            $table->string('desc');
            $table->string('state');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_sea_shipment_line');
    }
};
