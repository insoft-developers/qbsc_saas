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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->integer('paket_id');
            $table->integer('userid');
            $table->integer('comid');
            $table->integer('amount');
            $table->integer('payment_amount')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_with')->nullable();
            $table->datetime('payment_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
