<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi (fillable)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // admin / user
    ];

    /**
     * Hidden attribute
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attribute
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * RELASI KE RENTAL
     * Satu user bisa memiliki banyak rental
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function penyewa()
    {
        return $this->hasOne(Penyewa::class);
    }
}
