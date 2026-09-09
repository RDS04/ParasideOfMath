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
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'has_seen_onboarding')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('has_seen_onboarding')->default(false);
            });
        }

        if (Schema::hasTable('siswa') && !Schema::hasColumn('siswa', 'has_seen_onboarding')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->boolean('has_seen_onboarding')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'has_seen_onboarding')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('has_seen_onboarding');
            });
        }

        if (Schema::hasTable('siswa') && Schema::hasColumn('siswa', 'has_seen_onboarding')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropColumn('has_seen_onboarding');
            });
        }
    }
};
