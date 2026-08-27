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
        Schema::table('main_sliders', function (Blueprint $table) {
            $table->string('subtitle')->after('title')->nullable();
            $table->longText('content')->after('subtitle')->nullable();
            $table->json('comid')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_sliders', function (Blueprint $table) {
            //
        });
    }
};
