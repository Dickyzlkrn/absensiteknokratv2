<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Mahasiswa extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    protected $table = "mahasiswa";
    protected $primaryKey = "npm";
    protected $fillable = [
        'npm',
        'nama_mhs',
        'nohp_mhs',
        'prodi',
        'password',
        'foto',
        'tempat_pkl'

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getAuthPassword()
    {
        return $this->password; // Pastikan password digunakan untuk login
    }
}
