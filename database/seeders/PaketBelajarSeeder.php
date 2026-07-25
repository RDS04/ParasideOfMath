<?php

namespace Database\Seeders;

use App\Models\PaketBelajar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaketBelajarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        PaketBelajar::truncate();

        // Seed default Paket Belajar
        PaketBelajar::create([
            'nama_paket' => 'SD & SMP',
            'kategori' => 'Dasar',
            'deskripsi' => 'Memperkuat konsep matematika dasar sekolah dasar dan menengah.',
            'harga_min' => 50000,
            'harga_max' => 80000,
            'detail_1' => 'Privat 1 Orang: Rp 80.000',
            'detail_2' => 'Kelompok 2 Orang: Rp 70K/org',
            'detail_3' => 'Kelompok 3 Orang: Rp 60K/org',
            'detail_4' => 'Kelompok 4-7 Orang: Rp 50K/org',
            'detail_5' => 'Durasi belajar 90 menit',
            'is_populer' => false,
        ]);
        
        PaketBelajar::create([
            'nama_paket' => 'SMA',
            'kategori' => 'Menengah',
            'deskripsi' => 'Persiapan ujian sekolah reguler dan materi pemantapan SMA.',
            'harga_min' => 60000,
            'harga_max' => 90000,
            'detail_1' => 'Privat 1 Orang: Rp 90.000',
            'detail_2' => 'Kelompok 2 Orang: Rp 80K/org',
            'detail_3' => 'Kelompok 3 Orang: Rp 70K/org',
            'detail_4' => 'Kelompok 4-7 Orang: Rp 60K/org',
            'detail_5' => 'Durasi belajar 90 menit',
            'is_populer' => false,
        ]);

        PaketBelajar::create([
            'nama_paket' => "K' Angel / K' Sofia",
            'kategori' => 'Spesialis',
            'deskripsi' => 'Bimbingan privat intensif dibimbing langsung oleh Kak Angel atau Kak Sofia.',
            'harga_min' => 70000,
            'harga_max' => 125000,
            'detail_1' => 'Privat 1 Orang: Rp 125.000',
            'detail_2' => 'Kelompok 2 Orang: Rp 100K/org',
            'detail_3' => 'Kelompok 3 Orang: Rp 80K/org',
            'detail_4' => 'Kelompok 4-7 Orang: Rp 70K/org',
            'detail_5' => 'Durasi belajar 90 menit',
            'is_populer' => false,
        ]);

        PaketBelajar::create([
            'nama_paket' => "K' Ika",
            'kategori' => 'Spesialis Utama',
            'deskripsi' => 'Bimbingan belajar eksklusif bersama Kak Ika untuk hasil pemahaman optimal.',
            'harga_min' => 80000,
            'harga_max' => 150000,
            'detail_1' => 'Privat 1 Orang: Rp 150.000',
            'detail_2' => 'Kelompok 2 Orang: Rp 125K/org',
            'detail_3' => 'Kelompok 3 Orang: Rp 100K/org',
            'detail_4' => 'Kelompok 4-7 Orang: Rp 80K/org',
            'detail_5' => 'Durasi belajar 90 menit',
            'is_populer' => true,
        ]);

        PaketBelajar::create([
            'nama_paket' => 'SNBT / Olimpiade / Kuliah',
            'kategori' => 'Akademik Tinggi',
            'deskripsi' => 'Persiapan ujian seleksi PTN (SNBT), olimpiade, serta pendampingan materi kuliah.',
            'harga_min' => 125000,
            'harga_max' => 250000,
            'detail_1' => 'Privat 1 Orang: Rp 250.000',
            'detail_2' => 'Kelompok 2 Orang: Rp 200K/org',
            'detail_3' => 'Kelompok 3 Orang: Rp 150K/org',
            'detail_4' => 'Kelompok 4-7 Orang: Rp 125K/org',
            'detail_5' => 'Durasi belajar 90 menit',
            'is_populer' => false,
        ]);

        PaketBelajar::create([
            'nama_paket' => 'S2 / S3 / Naik Pangkat',
            'kategori' => 'Profesional',
            'deskripsi' => 'Persiapan akademis pascasarjana, kenaikan jabatan profesi, dan tes karir khusus.',
            'harga_min' => 175000,
            'harga_max' => 300000,
            'detail_1' => 'Privat 1 Orang: Rp 300.000',
            'detail_2' => 'Kelompok 2 Orang: Rp 250K/org',
            'detail_3' => 'Kelompok 3 Orang: Rp 200K/org',
            'detail_4' => 'Kelompok 4-7 Orang: Rp 175K/org',
            'detail_5' => 'Durasi belajar 90 menit',
            'is_populer' => false,
        ]);
    }
}
