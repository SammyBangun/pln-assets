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
        Schema::create('disruptions', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->autoIncrement();
            $table->string('nama_gangguan');
        });

        Schema::create('detail_disruptions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedInteger('jenis_gangguan');
            $table->foreign('jenis_gangguan')->references('id')->on('disruptions')->onDelete('cascade');
            $table->string('detail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_disruptions');
        Schema::dropIfExists('disruptions');
    }
};
