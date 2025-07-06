<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedInteger('user_pelapor');
            $table->foreign('user_pelapor')->references('id')->on('users')->onDelete('cascade');
            $table->string('aset');
            $table->foreign('aset')->references('serial_number')->on('assets')->onDelete('cascade');
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
