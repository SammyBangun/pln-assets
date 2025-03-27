<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_pelapor')->constrained('users')->onDelete('cascade');
            $table->string('aset');
            $table->foreign('aset')->references('serial_number')->on('assets')->onDelete('cascade');
            $table->string('laporan_kerusakan');
            $table->text('deskripsi');
            $table->string('tindak_lanjut')->nullable();
            $table->string('deskripsi_lanjut')->nullable();
            $table->string('realisasi')->nullable();
            $table->string('gambar');
            $table->string('gambar_konfirmasi')->nullable();
            $table->enum('status', ['Diterima', 'Dalam Proses', 'Selesai'])->default('Diterima');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
