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
        Schema::create('doc_chicks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('jam');
            $table->integer('satpam_id');
            $table->integer('jumlah');
            $table->integer('ekspedisi_id');
            $table->string('tujuan')->nullable();
            $table->string('no_polisi')->nullable();
            $table->integer('jenis')->nullable();
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
        Schema::dropIfExists('doc_chicks');
    }
};
