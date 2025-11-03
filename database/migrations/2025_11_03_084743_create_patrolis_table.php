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
        Schema::create('patrolis', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->date('tanggal');
            $table->time('jam');
            $table->integer('location_id');
            $table->string('location_code');
            $table->integer('satpam_id');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('note')->nullable();
            $table->integer('comid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrolis');
    }
};
