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
		Schema::create('pasien', function (Blueprint $table) {
			$table->id('pasien_id'); // Kolom pasien_id sebagai primary key
			$table->unsignedBigInteger('user_id'); // Kolom user_id (relasi dengan tabel users)
			$table->text('riwayat_medis'); // Kolom riwayat_medis (teks)
			$table->string('asuransi', 100); // Kolom asuransi (varchar)

			$table->timestamps(); // Kolom created_at dan updated_at

			// Menambahkan foreign key constraint
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
