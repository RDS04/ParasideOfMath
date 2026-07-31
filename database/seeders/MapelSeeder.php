<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapels = [
            ['nama_mapel' => 'Matematika Wajib', 'shift' => 2],
            ['nama_mapel' => 'Matematika Lanjut', 'shift' => 3],
            ['nama_mapel' => 'Matematika Wajib + Lanjut', 'shift' => 4],
            ['nama_mapel' => 'Fisika', 'shift' => 2],
            ['nama_mapel' => 'Kimia', 'shift' => 2],
            ['nama_mapel' => 'Biologi', 'shift' => 2],
            ['nama_mapel' => 'Bahasa Inggris', 'shift' => 2],
            ['nama_mapel' => 'Bahasa Indonesia', 'shift' => 1],
            ['nama_mapel' => 'Bahasa Indonesia', 'shift' => 2],
            ['nama_mapel' => 'Sejarah', 'shift' => 1],
            ['nama_mapel' => 'Sejarah', 'shift' => 2],
            ['nama_mapel' => 'Matematika TKA', 'shift' => 2],
            ['nama_mapel' => 'Bahasa Indonesia TKA', 'shift' => 2],
            ['nama_mapel' => 'Bahasa Inggris TKA', 'shift' => 2],
        ];

        foreach ($mapels as $mapel) {
            \App\Models\Mapel::firstOrCreate($mapel);
        }
    }
}
