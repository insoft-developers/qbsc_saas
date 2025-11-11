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
        Schema::create('kandang_kipas', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->date('tanggal');
            $table->time('jam');
            $table->integer('kandang_id');
            $table->integer('satpam_id');
            $table->string('kipas')->nullable();
            $table->string('note')->nullable();
            $table->string('foto')->nullable();
            $table->integer('comid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandang_kipas');
    }
};
