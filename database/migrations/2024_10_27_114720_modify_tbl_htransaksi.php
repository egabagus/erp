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
        Schema::table('tbl_htransaksi', function (Blueprint $table) {
            $table->integer('payment_status')->after('total')->default(0);
            $table->string('ship_date')->after('payment_status')->nullable();
            $table->string('freight_type')->after('ship_date')->nullable();
            $table->string('origin_country')->after('freight_type')->nullable();
            $table->string('port_embarkation')->after('origin_country')->nullable();
            $table->string('port_discharge')->after('port_embarkation')->nullable();
            $table->integer('freight_rp')->after('port_discharge')->nullable();
            $table->integer('insurance_rp')->after('freight_rp')->nullable();
            $table->integer('other')->after('insurance_rp')->nullable();
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
