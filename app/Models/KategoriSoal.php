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
    public static function availableClasses(string $jenjang): array
    {
        $jenjang = strtoupper($jenjang);
        if ($jenjang === 'SD') {
            return [1, 2, 3, 4, 5, 6, 'Olimpiade', 'Tes Masuk SMP'];
        } elseif ($jenjang === 'SMP') {
            return [1, 2, 3, 'Olimpiade', 'Tes Masuk SMA'];
        } elseif ($jenjang === 'SMA') {
            return [1, 2, 3, 'Olimpiade', 'UTBK'];
        }
        return [];
    }

    public static function availableSubKategori(string $jenjang, $kelas): array
    {
        if (in_array((string) $kelas, ['Olimpiade', 'Tes Masuk SMP', 'Tes Masuk SMA', 'UTBK'])) {
            return ['-'];
        }

        $subs = ['Semester 1', 'Semester 2'];

        $k = (int) $kelas;
        $isKelasAkhir =
            ($jenjang === 'SD'  && $k === 6) ||
            ($jenjang === 'SMP' && in_array($k, [3, 9])) ||
            ($jenjang === 'SMA' && in_array($k, [3, 12]));

        if ($isKelasAkhir) {
            $subs[] = 'TKA';
        }

        return $subs;
    }
}
