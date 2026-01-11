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
        Schema::table('Patrolis', function (Blueprint $table) {
            $table->string('jam_awal_patroli')->nullable()->after('comid');
            $table->string('jam_akhir_patroli')->nullable()->after('jam_awal_patroli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Patrolis', function (Blueprint $table) {
            //
        });
    }
};
