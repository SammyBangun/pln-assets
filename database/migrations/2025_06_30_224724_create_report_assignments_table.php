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
        Schema::create('report_assignments', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('report_id');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->string('petugas');
            $table->foreign('petugas')->references('id')->on('operators')->onDelete('cascade');
            $table->foreignId('realisasi')->references('id')->on('deliverables')->onDelete('cascade');
            $table->string('gambar_tindak_lanjut')->nullable();
            $table->date('tanggal_penugasan');
            $table->string('lokasi');
            $table->enum('status', ['Diterima', 'Diproses', 'Selesai'])->default('Diterima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_assignments');
    }
};
