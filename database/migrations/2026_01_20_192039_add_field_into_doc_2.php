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
        Schema::table('doc_chicks', function (Blueprint $table) {
            $table->json('doc_box_option')->nullable()->after('satpam_id');
            $table->string('nama_supir')->nullable()->after('ekspedisi_id');
            $table->integer('total_ekor')->nullable()->after('jumlah');
            $table->string('nomor_segel')->nullable()->after('no_polisi');
            $table->json('foto_doc')->nullable()->after('foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doc_chicks', function (Blueprint $table) {
            //
        });
    }
};
