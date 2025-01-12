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
		Schema::create('jadwal_dokter', function (Blueprint $table) {
			$table->id('jadwal_id'); // Auto increment id sebagai primary key
			$table->unsignedBigInteger('doctor_id'); // kolom doctor_id (berhubungan dengan tabel doctors)
			$table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']); // kolom hari (enum)
			$table->time('jam_mulai'); // kolom jam_mulai dengan tipe time
			$table->time('jam_selesai'); // kolom jam_selesai dengan tipe time
			$table->timestamps(); // timestamps untuk created_at dan updated_at

			// Menambahkan foreign key constraint untuk menghubungkan doctor_id dengan tabel doctors
			$table->foreign('doctor_id')->references('doctor_id')->on('doctors')->onDelete('cascade');
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_dokter');
    }
};
