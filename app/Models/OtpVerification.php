<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'role',
        'name',
        'phone',
        'password',
        'otp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Cek apakah OTP sudah kadaluarsa.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
