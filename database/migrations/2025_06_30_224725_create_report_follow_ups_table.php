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
        Schema::create('report_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->string('id_penugasan');
            $table->foreign('id_penugasan')->references('id')->on('report_assignments')->onDelete('cascade');
            $table->foreignId('id_gangguan')->constrained('disruptions')->onDelete('cascade');
            $table->string('id_detail_gangguan');
            $table->foreign('id_detail_gangguan')->references('id')->on('detail_disruptions')->onDelete('cascade');
            $table->text('catatan_tambahan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_follow_ups');
    }
};
