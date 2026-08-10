<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    use HasFactory;

    protected $table = 'hasil_ujians';

    protected $fillable = [
        'siswa_id',
        'kategori_soal_id',
        'jumlah_soal',
        'jumlah_benar',
        'jumlah_salah',
        'nilai',
        'jawaban_siswa',
    ];

    protected $casts = [
        'jawaban_siswa' => 'array',
        'nilai' => 'float',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriSoal::class, 'kategori_soal_id');
    }
}
