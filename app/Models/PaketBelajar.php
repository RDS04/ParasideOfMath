<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketBelajar extends Model
{
    use HasFactory;

    protected $table = 'paket_belajar';

    protected $fillable = [
        'nama_paket',
        'kategori',
        'deskripsi',
        'harga_min',
        'harga_max',
        'detail_1',
        'detail_2',
        'detail_3',
        'detail_4',
        'detail_5',
        'is_populer',
    ];
}
