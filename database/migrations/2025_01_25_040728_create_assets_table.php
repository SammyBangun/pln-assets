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
        $table->id('id_asset'); // AUTO_INCREMENT primary key
        $table->string('name', 50)->nullable();
        $table->string('type', 50)->nullable();
        $table->string('series', 50)->nullable();
        $table->date('tgl_beli')->nullable();
        $table->date('last_service')->nullable();
        $table->timestamps(); // Optional: created_at & updated_at
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