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
        Schema::create('tbl_sea_shipment', function (Blueprint $table) {
            $table->id('id_sea_shipment');
            $table->string('no_aju')->nullable();
            $table->date('date')->nullable();
            $table->string('origin');
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            $table->string('value_key')->nullable()->unique();
            $table->boolean('is_printed')->default(false);
            $table->integer('printcount')->nullable();
            $table->timestamp('printdate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_sea_shipment');
    }
};
