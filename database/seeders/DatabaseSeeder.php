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
        $dummySiswas = [
            [
                'name' => 'Siswa Test',
                'email' => 'siswa@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'paket_id' => $firstPaketId,
                'tipe_paket' => 'Mapel: Matematika | Hari: Senin,Rabu | Sesi: 6x | Guru: Kak Ika',
                'whatsapp' => '081234567890',
                'sekolah' => 'SMA Negeri 1 Jakarta',
                'status' => 'active',
                'biodata' => [
                    'nisn' => '1234567890',
                    'kelas' => '11 IPA',
                    'alamat' => 'Jl. Merdeka No. 10',
                    'nama_orang_tua' => 'Budi Pendamping',
                    'no_hp_orang_tua' => '081298765432',
                    'mapel_jadwal' => ['Matematika'],
                    'sesi_per_mapel' => [6],
                    'hari_per_mapel' => [['Senin', 'Rabu']],
                    'jam_per_mapel' => [['jam_mulai' => '09:00', 'jam_selesai' => '10:30']],
                    'tutor_per_mapel' => ['Matematika' => 'Kak Ika'],
                ],
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'paket_id' => $firstPaketId,
                'tipe_paket' => 'Mapel: Matematika | Hari: Selasa,Kamis | Sesi: 6x | Guru: Kak Angel',
                'whatsapp' => '081987654321',
                'sekolah' => 'SMP Negeri 5 Surabaya',
                'status' => 'active',
                'biodata' => [
                    'nisn' => '0987654321',
                    'kelas' => '9 SMP',
                    'alamat' => 'Jl. Pemuda No. 12',
                    'nama_orang_tua' => 'Santoso',
                    'no_hp_orang_tua' => '081987654321',
                    'mapel_jadwal' => ['Matematika'],
                    'sesi_per_mapel' => [6],
                    'hari_per_mapel' => [['Selasa', 'Kamis']],
                    'jam_per_mapel' => [['jam_mulai' => '14:00', 'jam_selesai' => '15:30']],
                    'tutor_per_mapel' => ['Matematika' => 'Kak Angel'],
                ],
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'paket_id' => $firstPaketId,
                'tipe_paket' => 'Mapel: Matematika',
                'whatsapp' => '081345678901',
                'sekolah' => 'SMA Negeri 2 Bandung',
                'status' => 'under_review',
                'bukti_transfer' => 'dummy_bukti.jpg',
            ],
            [
                'name' => 'Siswa Dummy',
                'email' => 'siswa@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'whatsapp' => '081122334455',
                'sekolah' => 'SMA Negeri 1 Jakarta',
                'status' => 'pending',
            ],
        ];

        foreach ($dummySiswas as $siswaData) {
            \App\Models\Siswa::updateOrCreate(
                ['email' => $siswaData['email']],
                $siswaData
            );
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
