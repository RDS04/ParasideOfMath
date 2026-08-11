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
        Schema::create('kategori_soals', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang'); // SD, SMP, SMA
            $table->string('kelas');
            $table->string('sub_kategori'); // Semester 1, Semester 2, TKA, etc.
            $table->string('nama_kategori'); // e.g. Matematika, IPA, Bab 1, etc.
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('bank_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_soal_id')->constrained('kategori_soals')->onDelete('cascade');
            $table->integer('nomor');
            $table->text('soal');
            $table->text('opsi_a');
            $table->text('opsi_b');
            $table->text('opsi_c');
            $table->text('opsi_d');
            $table->string('kunci_jawaban', 2); // A, B, C, D
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soals');
        Schema::dropIfExists('kategori_soals');
    }
};
