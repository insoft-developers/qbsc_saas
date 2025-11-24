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
        Schema::create('laporan_situasis', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->integer('satpam_id');
            $table->string('laporan');
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
        Schema::dropIfExists('laporan_situasis');
    }
};
