<?php

// app/Models/User.php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Hapus jika tidak dipakai
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // PASTIKAN USE STATEMENT INI ADA DAN BENAR

class User extends Authenticatable // Mungkin juga implements MustVerifyEmail jika diperlukan
{
    use HasApiTokens, HasFactory, Notifiable; // PASTIKAN TRAIT DIPANGGIL DI SINI

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Pastikan 'role' ada di $fillable jika Anda mengisinya saat create/update
    ];

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
    protected function casts(): array // Untuk Laravel 10+
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Untuk Laravel 9 ke bawah, gunakan protected $casts property:
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];


    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }
}