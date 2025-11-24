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
        Schema::create('tamus', function (Blueprint $table) {
            $table->id();
            $table->integer('satpam_id');
            $table->string('nama_tamu');
            $table->string('tujuan')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('foto')->nullable();
            $table->integer('is_status');
            $table->string('catatan')->nullable();
            $table->integer('comid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamus');
    }
};
