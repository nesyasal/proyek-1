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
		Schema::create('konsultasi', function (Blueprint $table) {
			$table->id('konsultasi_id'); // Kolom id untuk primary key
			$table->unsignedBigInteger('pasien_id'); // Kolom pasien_id (relasi dengan tabel pasien)
			$table->unsignedBigInteger('doctor_id'); // Kolom doctor_id (relasi dengan tabel doctors)
			$table->dateTime('tanggal_konsultasi'); // Kolom tanggal_konsultasi dengan tipe datetime
			$table->enum('status', ['terjawab', 'belum dijawab', 'reviewed']); // Kolom status (enum)
			$table->text('keluhan_pasien'); // Kolom keluhan_pasien (teks)
			$table->text('balasan_dokter')->nullable(); // Kolom balasan_dokter (teks, nullable)

			$table->timestamps(); // Kolom created_at dan updated_at

			// Menambahkan foreign key constraints
			$table->foreign('pasien_id')->references('pasien_id')->on('pasien')->onDelete('cascade');
			$table->foreign('doctor_id')->references('doctor_id')->on('doctors')->onDelete('cascade');
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasi');
    }
};
