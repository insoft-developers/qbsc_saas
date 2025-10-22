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
            $table->renameColumn('userid', 'comid');
            $table->string('whatsapp')->after('badge_id');
            $table->string('password')->after('whatsapp');
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
