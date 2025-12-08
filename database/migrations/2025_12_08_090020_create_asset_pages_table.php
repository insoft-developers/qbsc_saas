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
        Schema::create('asset_pages', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name');
            $table->text('asset_description');
            $table->string('android_link');
            $table->string('ios_link');
            $table->integer('copy_number');
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_pages');
    }
};
