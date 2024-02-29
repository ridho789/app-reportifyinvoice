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
        Schema::create('table_sea_shipment', function (Blueprint $table) {
            $table->id('id_sea_shipment');
            $table->string('no');
            $table->date('date');
            $table->string('tot_ships');
            $table->string('tot_pkgs');
            $table->string('tot_weight');
            $table->string('tot_vol');
            $table->date('etd');
            $table->date('eta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_sea_shipment');
    }
};
