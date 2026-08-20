<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('youtube_links', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('youtube_url');
            $table->string('youtube_id');
            $table->string('kategori')->default('Tutorial');
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('youtube_links');
    }
};
