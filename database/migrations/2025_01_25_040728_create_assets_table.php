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
                $table->string('divisi');
                $table->foreign('divisi')->references('id')->on('divisions')->onDelete('cascade');
                $table->string('nama', 50);
                $table->string('tipe', 50);
                $table->string('seri', 50);
                $table->string('gambar');
                $table->date('tanggal_beli');
                $table->date('terakhir_servis')->nullable();
                $table->string('lokasi');
                $table->enum('status_aset', ['Aktif', 'Dalam Penanganan', 'Hilang'])->default('Aktif');
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
