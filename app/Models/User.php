<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is Master.
     */
    public function isMaster(): bool
    {
        return $this->role === 'master';
    }

    /**
     * Check if user is Admin or Master.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'master';
    }

    /**
     * Check if user is Guru (Teacher).
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Check if user is Siswa (Student).
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Get the Guru profile associated with the User.
     */
    public function guruProfile()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    /**
     * Get or create Guru profile for this user.
     */
    public function getOrCreateGuruProfile(): Guru
    {
        return $this->guruProfile ?? Guru::create(['user_id' => $this->id]);
    }
}
