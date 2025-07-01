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
        Schema::create('identifications', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->autoIncrement();
            $table->string('identifikasi_masalah');
        });

        Schema::create('report_identifications', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->autoIncrement();
            $table->string('report_id');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->unsignedInteger('identifikasi_masalah');
            $table->foreign('identifikasi_masalah')->references('id')->on('identifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identifications');
        Schema::dropIfExists('report_identifications');
    }
};
