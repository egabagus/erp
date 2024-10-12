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
        Schema::create('tbl_header_po', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_number');
            $table->dateTime('po_date');
            $table->string('purchaser');
            $table->string('vendor_code');
            $table->integer('total');
            $table->string('payment_terms');
            $table->string('incoterms');
            $table->integer('app_operational')->default(0);
            $table->integer('app_finance')->default(0);
            $table->integer('vendor_confirm')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_header_po');
    }
};
