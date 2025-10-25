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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->datetime('tanggal');
            $table->integer('satpam_id');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->datetime('jam_masuk')->nullable();
            $table->datetime('jam_keluar')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('description')->nullable();
            $table->integer('comid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
