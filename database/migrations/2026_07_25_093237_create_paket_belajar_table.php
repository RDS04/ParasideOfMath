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
        Schema::create('paket_belajar', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->string('kategori');
            $table->text('deskripsi')->nullable();
            $table->integer('harga_min');
            $table->integer('harga_max');
            $table->string('detail_1')->nullable();
            $table->string('detail_2')->nullable();
            $table->string('detail_3')->nullable();
            $table->string('detail_4')->nullable();
            $table->string('detail_5')->nullable();
            $table->boolean('is_populer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_belajar');
    }
};
