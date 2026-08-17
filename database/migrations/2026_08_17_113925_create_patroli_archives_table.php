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
        Schema::create('patroli_archives', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('patroli_id');
            $table->unsignedBigInteger('company_id');

            $table->date('tanggal');

            $table->string('original_path');

            $table->string('google_drive_file_id')->nullable();
            $table->string('google_drive_folder_id')->nullable();

            $table->unsignedBigInteger('file_size')->nullable();

            $table->enum('status', [
                'pending',
                'uploading',
                'uploaded',
                'failed',
                'deleted',
            ])->default('pending');

            $table->text('error_message')->nullable();

            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['patroli_id'],
                'patroli_archives_patroli_id_unique'
            );

            $table->index('company_id');
            $table->index('tanggal');
            $table->index('status');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patroli_archives');
    }
};
