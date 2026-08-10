<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasFactory;

    protected $table = 'bank_soals';

    protected $fillable = [
        'kategori_soal_id',
        'nomor',
        'soal',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'kunci_jawaban',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriSoal::class, 'kategori_soal_id');
    }
}
