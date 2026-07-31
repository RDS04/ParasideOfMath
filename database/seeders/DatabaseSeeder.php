<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (User::where('email', 'test@example.com')->doesntExist()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call([
            PaketBelajarSeeder::class,
            MapelSeeder::class,
        ]);

        if (\App\Models\Rekening::count() == 0) {
            \App\Models\Rekening::create([
                'tipe' => 'bank',
                'nama_bank' => 'BCA',
                'nomor_rekening' => '315-098-7654',
                'atas_nama' => 'LBB Paradise of Math',
            ]);
            \App\Models\Rekening::create([
                'tipe' => 'bank',
                'nama_bank' => 'BNI',
                'nomor_rekening' => '088-776-5544',
                'atas_nama' => 'LBB Paradise of Math',
            ]);
            \App\Models\Rekening::create([
                'tipe' => 'bank',
                'nama_bank' => 'BSI',
                'nomor_rekening' => '711-223-3445',
                'atas_nama' => 'LBB Paradise of Math',
            ]);
            \App\Models\Rekening::create([
                'tipe' => 'ewallet',
                'nama_bank' => 'DANA',
                'nomor_rekening' => '0812-3456-7890',
                'atas_nama' => 'LBB Paradise of Math',
            ]);
        }
    }
}
