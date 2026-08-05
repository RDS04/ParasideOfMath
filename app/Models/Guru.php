<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';

    protected $fillable = [
        'user_id',
        'no_telp',
        'alamat',
        'spesialisasi',
        'gelar',
        'pendidikan_terakhir',
        'pengalaman_mengajar',
        'bio_singkat',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if Guru's biodata is complete.
     */
    public function isComplete(): bool
    {
        return !empty($this->no_telp)
            && !empty($this->alamat)
            && !empty($this->spesialisasi)
            && !empty($this->pendidikan_terakhir);
    }
}
