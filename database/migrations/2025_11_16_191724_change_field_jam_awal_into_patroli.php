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
        Schema::table('patrolis', function (Blueprint $table) {
            $table->string('jam_awal')->nullable()->change();
            $table->string('jam_akhir')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patrolis', function (Blueprint $table) {
            //
        });
    }
};
