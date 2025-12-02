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
        Schema::create('paket_langganans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->string('subttile');
            $table->integer('harga');
            $table->integer('periode');
            $table->integer('jumlah_satpam');
            $table->integer('jumlah_lokasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_langganans');
    }
};
