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
        Schema::create('tbl_supplier', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_supp');
            $table->string('nama_supp');
            $table->string('pic');
            $table->string('handphone');
            $table->string('email');
            $table->string('alamat');
            $table->string('deskripsi');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_supplier');
    }
};
