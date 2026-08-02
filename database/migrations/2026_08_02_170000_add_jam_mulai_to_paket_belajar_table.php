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
        Schema::table('paket_belajar', function (Blueprint $table) {
            $table->string('jam_mulai')->default('15:30')->after('detail_5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_belajar', function (Blueprint $table) {
            $table->dropColumn('jam_mulai');
        });
    }
};
