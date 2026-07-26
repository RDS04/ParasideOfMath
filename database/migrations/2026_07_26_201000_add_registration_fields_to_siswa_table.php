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
        Schema::table('siswa', function (Blueprint $table) {
            $table->unsignedBigInteger('paket_id')->nullable()->after('password');
            $table->string('tipe_paket')->nullable()->after('paket_id');
            $table->string('whatsapp')->nullable()->after('tipe_paket');
            $table->string('sekolah')->nullable()->after('whatsapp');
            $table->string('bukti_transfer')->nullable()->after('sekolah');
            $table->string('status')->default('pending')->after('bukti_transfer'); // pending, under_review, active
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['paket_id', 'tipe_paket', 'whatsapp', 'sekolah', 'bukti_transfer', 'status']);
        });
    }
};
