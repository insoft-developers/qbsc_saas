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
        Schema::table('running_texts', function (Blueprint $table) {
            $table->removeColumn('admin_whatsapp');
            $table->integer('comid')->after('text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('running_texts', function (Blueprint $table) {
            //
        });
    }
};
