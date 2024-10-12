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
        Schema::create('tbl_barang', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('deskripsi')->nullable();
            $table->string('harga');
            $table->string('kategori');
            $table->string('satuan');
            $table->string('foto')->nullable();
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
