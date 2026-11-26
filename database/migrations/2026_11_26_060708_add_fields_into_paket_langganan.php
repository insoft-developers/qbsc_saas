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
        Schema::table('paket_langganans', function (Blueprint $table) {
            $table->integer('urutan')->default(0)->after('is_active');
            $table->string('comids')->nullable()->after('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_langganans', function (Blueprint $table) {
            //
        });
    }
};
