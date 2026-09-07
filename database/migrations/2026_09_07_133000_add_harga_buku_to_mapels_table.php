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
        Schema::table('mapels', function (Blueprint $table) {
            if (!Schema::hasColumn('mapels', 'harga_buku')) {
                $table->unsignedBigInteger('harga_buku')->default(0)->after('shift');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapels', function (Blueprint $table) {
            if (Schema::hasColumn('mapels', 'harga_buku')) {
                $table->dropColumn('harga_buku');
            }
        });
    }
};
