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
        Schema::create('tbl_hbapb', function (Blueprint $table) {
            $table->id();
            $table->string('bapb_no');
            $table->string('po_number');
            $table->date('date');
            $table->string('vendor_code');
            $table->integer('status')->default(1);
            $table->integer('total');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tbl_dbapb', function (Blueprint $table) {
            $table->id();
            $table->string('bapb_no');
            $table->string('kode_barang');
            $table->integer('stok_awal');
            $table->integer('stok_terima');
            $table->integer('stok_akhir');
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_hbapb');
        Schema::dropIfExists('tbl_dbapb');
    }
};
