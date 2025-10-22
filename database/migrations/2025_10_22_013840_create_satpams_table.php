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
        Schema::create('satpams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('badge_id')->nullable();
            $table->boolean('face_registered')->default(false);
            $table->integer('userid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satpams');
    }
};
