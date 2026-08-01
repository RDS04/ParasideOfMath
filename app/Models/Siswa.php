<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'siswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'paket_id',
        'tipe_paket',
        'whatsapp',
        'sekolah',
        'bukti_transfer',
        'status',
        'biodata',
    ];

    /**
     * Relasi ke Paket Belajar.
     */
    public function paket()
    {
        return $this->belongsTo(PaketBelajar::class, 'paket_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'biodata' => 'array',
        ];
    }

    /**
     * Check if user is Admin.
     */
    public function isAdmin(): bool
    {
        return false;
    }

    /**
     * Check if user is Guru (Teacher).
     */
    public function isGuru(): bool
    {
        return false;
    }

    /**
     * Check if user is Siswa (Student).
     */
    public function isSiswa(): bool
    {
        return true;
    }
}
