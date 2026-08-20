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
        Schema::create('riwayat_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->unsignedBigInteger('paket_id')->nullable();
            $table->string('tipe_paket_snapshot')->nullable(); // snapshot detail paket saat transaksi
            $table->string('bukti_transfer');
            $table->string('payment_method')->default('bank'); // bank, ewallet, tunai
            $table->unsignedInteger('jumlah_sesi')->default(1);
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->enum('status', ['under_review', 'approved', 'rejected'])->default('under_review');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pembayarans');
    }
};
