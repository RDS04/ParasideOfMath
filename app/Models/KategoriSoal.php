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
        'sub_kategori',
        'nama_kategori',
        'deskripsi',
    ];

    public function bankSoals()
    {
        return $this->hasMany(BankSoal::class, 'kategori_soal_id')->orderBy('nomor', 'asc');
    }
}
