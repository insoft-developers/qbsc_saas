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
            $table->integer('jumlah_farm')->nullable()->after('jumlah_lokasi');
            $table->boolean('is_broadcast')->after('jumlah_farm')->nullable();
            $table->integer('jumlah_user_admin')->after('is_broadcast')->nullable();
            $table->boolean('is_scan_tamu')->after('jumlah_user_admin')->nullable();
            $table->boolean('is_user_area')->after('is_scan_tamu')->nullable();
            $table->boolean('is_mobile_app')->after('is_user_area')->nullable();
            $table->boolean('is_google_meet')->after('is_mobile_app')->nullable();
            $table->boolean('is_request_feature')->after('is_google_meet')->nullable();

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
