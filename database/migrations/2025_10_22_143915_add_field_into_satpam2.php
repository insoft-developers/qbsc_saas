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
        Schema::table('satpams', function (Blueprint $table) {
            $table->string('foto')->after('comid')->nullable();
            $table->tinyInteger('is_active')->after('foto')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('satpams', function (Blueprint $table) {
            //
        });
    }
};
