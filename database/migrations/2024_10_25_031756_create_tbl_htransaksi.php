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
        Schema::create('tbl_htransaksi', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->timestamp('date');
            $table->string('customer_id');
            $table->string('user_id');
            $table->integer('status')->default(1);
            $table->string('po_number');
            $table->longText('note');
            $table->longText('payment_note');
            $table->integer('subtotal');
            $table->integer('ppn_total');
            $table->integer('disc_total');
            $table->integer('total');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_htransaksi');
    }
};
