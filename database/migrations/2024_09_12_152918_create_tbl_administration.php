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
        Schema::create('tbl_administration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('brand_name');
            $table->string('alamat');
            $table->string('handphone');
            $table->string('email');
            $table->string('fax');
            $table->string('telp');
            $table->string('website');
            $table->string('logo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_administration');
    }
};
