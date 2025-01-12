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
		Schema::create('review', function (Blueprint $table) {
			$table->id('id_review'); // Kolom id_review sebagai primary key
			$table->unsignedBigInteger('konsultasi_id'); // Kolom konsultasi_id (relasi dengan tabel konsultasi)
			$table->string('rating', 20); // Kolom rating (varchar)
			$table->text('pesan'); // Kolom pesan (teks)

			$table->timestamps(); // Kolom created_at dan updated_at

			// Menambahkan foreign key constraint
			$table->foreign('konsultasi_id')->references('konsultasi_id')->on('konsultasi')->onDelete('cascade');
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
