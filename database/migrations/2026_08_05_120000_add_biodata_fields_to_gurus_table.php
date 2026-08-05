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
        Schema::table('gurus', function (Blueprint $table) {
            $table->string('gelar')->nullable()->after('spesialisasi');
            $table->string('pendidikan_terakhir')->nullable()->after('gelar');
            $table->string('pengalaman_mengajar')->nullable()->after('pendidikan_terakhir');
            $table->text('bio_singkat')->nullable()->after('pengalaman_mengajar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn([
                'gelar',
                'pendidikan_terakhir',
                'pengalaman_mengajar',
                'bio_singkat',
            ]);
        });
    }
};
