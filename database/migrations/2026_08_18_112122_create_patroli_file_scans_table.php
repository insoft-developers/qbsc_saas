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
        Schema::create('patroli_file_scans', function (Blueprint $table) {
            $table->id();

            $table->string('file_name');
            $table->string('file_path')->unique();

            $table->foreignId('company_id')
                ->nullable()
                ->index()
                ->constrained('companies')
                ->nullOnDelete();

            $table->string('company_name')
                ->nullable();

            $table->unsignedBigInteger('file_size')
                ->default(0);

            $table->enum('status', [
                'exists',
                'orphan',
            ])->index();

            $table->timestamp('scanned_at')
                ->nullable();

            $table->timestamps();

            $table->index(['status', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patroli_file_scans');
    }
};
