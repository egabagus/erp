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
        Schema::create('tbl_hpackinglist', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi');
            $table->date('date');
            $table->string('cust_id');
            $table->string('user_id');
            $table->integer('qty_total');
            $table->integer('nett_total');
            $table->integer('gross_total');
            $table->string('desc')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_packing_lists');
    }
};
