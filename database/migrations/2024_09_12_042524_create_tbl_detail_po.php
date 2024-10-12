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
        Schema::create('tbl_detail_po', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_number');
            $table->string('item_code');
            $table->integer('qty');
            $table->string('disc');
            $table->integer('discrp');
            $table->string('tax');
            $table->integer('taxrp');
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_detail_po');
    }
};
