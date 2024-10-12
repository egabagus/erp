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
        Schema::create('tbl_hreq_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('req_number');
            $table->string('req_by');
            $table->dateTime('date');
            $table->dateTime('due_date');
            $table->integer('app_manager');
            $table->string('note')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_hreq_order');
    }
};
