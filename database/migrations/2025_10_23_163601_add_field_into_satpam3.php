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
            $table->dropColumn('foto');
            $table->dropColumn('face_registered');
            $table->string('face_photo_path')->nullable()->after('comid'); // path ke foto
            $table->binary('face_embedding')->nullable()->after('face_photo_path'); 
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
