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
        Schema::table('tamus', function (Blueprint $table) {
            $table->string('uuid')->unique()->after('id');
            $table->integer('jumlah_tamu')->after('nama_tamu');
            $table->datetime('arrive_at')->nullable()->after('catatan');
            $table->datetime('leave_at')->nullable()->after('arrive_at');
            $table->integer('satpam_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamus', function (Blueprint $table) {
            //
        });
    }
};
