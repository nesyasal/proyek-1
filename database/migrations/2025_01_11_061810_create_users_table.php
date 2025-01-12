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
		Schema::create('users', function (Blueprint $table) {
			$table->bigIncrements('id')->unsigned(); // Tipe bigint UNSIGNED
			$table->string('name'); // Kolom name dengan panjang varchar(255)
			$table->string('email')->unique(); // Kolom email dengan panjang varchar(255) dan unique
			$table->string('password'); // Kolom password dengan panjang varchar(255)
			$table->string('username')->nullable(); // Kolom username dengan panjang varchar(255), nullable
			$table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->nullable(); // Kolom jenis_kelamin dengan tipe enum
			$table->date('tanggal_lahir')->nullable(); // Kolom tanggal_lahir dengan tipe date, nullable
			$table->text('alamat')->nullable(); // Kolom alamat dengan tipe text, nullable
			$table->string('no_telepon')->nullable(); // Kolom no_telepon dengan panjang varchar(255), nullable
			$table->enum('tipe_pengguna', ['Dokter', 'Pasien', 'Admin'])->nullable(); // Kolom tipe_pengguna dengan tipe enum
			$table->enum('status', ['pending', 'approved', 'rejected'])->nullable(); // Kolom status dengan tipe enum

			$table->timestamps(); // Kolom created_at dan updated_at secara otomatis ditambahkan
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
