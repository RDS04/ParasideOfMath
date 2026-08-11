<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSoal extends Model
{
    use HasFactory;

    protected $table = 'kategori_soals';

    protected $fillable = [
        'jenjang',
        'kelas',
        'sub_kategori',
        'nama_kategori',
        'deskripsi',
    ];

    public function bankSoals()
    {
        return $this->hasMany(BankSoal::class, 'kategori_soal_id')->orderBy('nomor', 'asc');
    }
    public static function availableSubKategori(string $jenjang, $kelas): array
    {
        $subs = ['Semester 1', 'Semester 2'];

        $isKelasAkhir =
            ($jenjang === 'SD'  && (int) $kelas === 6) ||
            (in_array($jenjang, ['SMP', 'SMA']) && (int) $kelas === 3);

        if ($isKelasAkhir) {
            $subs[] = 'TKA';
        }

        return $subs;
    }
}
