<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Admin extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->where('role', 'admin');
        });

        static::creating(function ($admin) {
            $admin->role = 'admin';
        });
    }
}
