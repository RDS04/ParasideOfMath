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
        if (User::where('email', 'ica@gmail.com.com')->doesntExist()) {
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

        $firstPaketId = \App\Models\PaketBelajar::first()?->id;

        // 3. Seed / Update Akun Dummy Siswa (Siswa table) untuk kemudahan Testing Lokal
        $dummySiswa = \App\Models\Siswa::where('email', 'siswa@example.com')->first();
        if (!$dummySiswa) {
            \App\Models\Siswa::create([
                'name' => 'Siswa Test',
                'email' => 'siswa@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'whatsapp' => '081234567890',
                'sekolah' => 'SMA Negeri 1',
                'status' => 'active',
                'paket_id' => $firstPaketId,
                'tipe_paket' => 'Privat 1 on 1 | Mapel: Matematika | Hari: Senin,Kamis | Sesi: 8x | Guru: Kak Ika',
                'biodata' => [
                    'mapel_jadwal' => ['Matematika'],
                    'sesi_per_mapel' => [8],
                    'hari_per_mapel' => [
                        ['Senin', 'Kamis']
                    ],
                    'tanggal_mulai_per_mapel' => [date('Y-m-01')],
                    'jam_per_mapel' => [
                        ['jam_mulai' => '15:30', 'jam_selesai' => '17:00']
                    ],
                    'tutor_per_mapel' => ['Matematika' => 'Kak Ika'],
                    'tanggal_mulai' => date('Y-m-01'),
                    'jumlah_pertemuan' => 8,
                    'hari_pertemuan' => ['Senin', 'Kamis']
                ]
            ]);
        } else if (!$dummySiswa->paket_id && $firstPaketId) {
            $dummySiswa->paket_id = $firstPaketId;
            $dummySiswa->save();
        }

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
