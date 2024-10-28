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
        Schema::create('tbl_dproforma_inv', function (Blueprint $table) {
            $table->id();
            $table->string('proforma_no');
            $table->string('item_code');
            $table->integer('qty');
            $table->integer('ppn');
            $table->integer('disc');
            $table->integer('total_price');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_dproforma_inv');
    }
};
