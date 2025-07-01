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
            $table->id();
            $table->string('id_gangguan_hardware');
            $table->foreign('id_gangguan_hardware')->references('id')->on('detail_disruptions')->onDelete('cascade');
            $table->string('detail_merek_hardware');
            $table->string('jumlah');
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
