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
		Schema::create('doctors', function (Blueprint $table) {
			$table->id('doctor_id'); // Auto increment id sebagai primary key
			$table->unsignedBigInteger('user_id'); // kolom user_id (berhubungan dengan tabel users)
			$table->string('spesialisasi', 100); // kolom spesialisasi
			$table->string('kualifikasi', 100); // kolom kualifikasi
			$table->string('pengalaman', 255); // kolom pengalaman
			$table->timestamps(); // timestamps untuk created_at dan updated_at

			// Menambahkan foreign key constraint untuk menghubungkan user_id dengan tabel users
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
