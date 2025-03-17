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
            $table->foreign('aset')->references('serial_number')->on('assets')->onDelete('cascade'); // Foreign key dari tabel assets
            $table->string('laporan_kerusakan');
            $table->text('deskripsi');
            $table->string('gambar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
