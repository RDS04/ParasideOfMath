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

        // 1. Seed Administrator (users table)
        if (User::where('email', 'admin@example.com')->doesntExist()) {
            User::create([
                'name' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        // 2. Seed Guru / Tutor (users & gurus table)
        $guruSeeds = [
            [
                'name' => 'Kak Ika',
                'email' => 'ika@gmail.com',
                'spesialisasi' => 'Matematika (Master)',
            ],
            [
                'name' => 'Kak Angel',
                'email' => 'angel@gmail.com',
                'spesialisasi' => 'Matematika & Bahasa Inggris (Co Master)',
            ],
            [
                'name' => 'Kak Sofia',
                'email' => 'sofia@gmail.com',
                'spesialisasi' => 'Matematika & Bahasa Inggris (Co Master)',
            ],
        ];

        foreach ($guruSeeds as $g) {
            if (User::where('email', $g['email'])->doesntExist()) {
                $guruUser = User::create([
                    'name' => $g['name'],
                    'email' => $g['email'],
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'role' => 'guru',
                ]);

                \App\Models\Guru::create([
                    'user_id' => $guruUser->id,
                    'no_telp' => '08123456789',
                    'alamat' => 'Jl. Pendidikan No. 45',
                    'spesialisasi' => $g['spesialisasi'],
                    'status' => 'aktif',
                ]);
            }
        }

        // Tetap pertahankan 1 akun guru generik buat testing lama
        if (User::where('email', 'ica@gmail.com')->doesntExist()) {
            $guruUser = User::create([
                'name' => 'Guru Test',
                'email' => 'ica@gmail.com.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'guru',
            ]);

            \App\Models\Guru::create([
                'user_id' => $guruUser->id,
                'no_telp' => '08123456789',
                'alamat' => 'Jl. Pendidikan No. 45',
                'spesialisasi' => 'Matematika SMA / Wajib',
                'status' => 'aktif',
            ]);
        }
        $this->call([
            MasterUserSeeder::class,
            PaketBelajarSeeder::class,
            MapelSeeder::class,
            BiodataSeeder::class,
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
