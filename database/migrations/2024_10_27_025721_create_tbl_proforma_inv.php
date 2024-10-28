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
        Schema::create('tbl_hproforma_inv', function (Blueprint $table) {
            $table->id();
            $table->string('proforma_no');
            $table->timestamp('date');
            $table->string('cutomer_id');
            $table->string('freight_type')->nullable();
            $table->timestamp('ship_date')->nullable();
            $table->integer('gross')->nullable();
            $table->integer('qty_total')->nullable();
            $table->longText('terms')->nullable();
            $table->string('origin_country')->nullable();
            $table->string('port_embarkation')->nullable();
            $table->string('port_discharge')->nullable();
            $table->integer('subtotal')->default(0);
            $table->integer('taxrp')->default(0);
            $table->integer('discrp')->default(0);
            $table->integer('freight')->default(0);
            $table->integer('insurance')->default(0);
            $table->integer('other')->default(0);
            $table->integer('totalrp')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_proforma_inv');
    }
};
