<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->string('serial_number', 50)->primary();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('name', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('series', 50)->nullable();
            $table->string('gambar')->nullable();
            $table->date('tgl_beli')->nullable();
            $table->date('last_service')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
