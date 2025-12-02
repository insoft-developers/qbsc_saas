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
            $table->renameColumn('subttile', 'subtitle');
            $table->renameColumn('compay_type', 'company_type');
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
