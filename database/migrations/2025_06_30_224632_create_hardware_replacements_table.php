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
        Schema::create('hardware_replacements', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->autoIncrement();
            $table->unsignedInteger('id_tindak_lanjut');
            $table->foreign('id_tindak_lanjut')->references('id')->on('report_follow_ups')->onDelete('cascade');
            $table->string('nama_komponen');
            $table->foreign('nama_komponen')->references('id')->on('detail_disruptions')->onDelete('cascade');
            $table->string('detail_merek_hardware');
            $table->integer('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hardware_replacements');
    }
};
