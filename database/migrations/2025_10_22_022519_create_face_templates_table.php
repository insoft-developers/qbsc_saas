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
        Schema::create('face_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('satpam_id')->index();
            $table->string('image_path')->nullable();
            $table->json('embedding')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('satpam_id')->references('id')->on('satpams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_templates');
    }
};
