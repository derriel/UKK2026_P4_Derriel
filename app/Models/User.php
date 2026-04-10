<?php

// Namespace untuk model
namespace App\Models;

// Import trait dan class yang diperlukan
// use Illuminate\Contracts\Auth\MustVerifyEmail; // Tidak digunakan
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Model User yang mewakili tabel users di database
class User extends Authenticatable
{
    // Menggunakan trait HasFactory untuk factory dan Notifiable untuk notifikasi
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Ini untuk keamanan agar tidak sembarang field bisa diupdate.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',     // Nama lengkap pengguna
        'email',    // Email pengguna
        'password', // Password yang di-hash
        'role_id',  // ID role pengguna
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi (misal ke JSON).
     * Ini untuk keamanan agar password tidak terlihat.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',       // Sembunyikan password
        'remember_token', // Sembunyikan token remember me
    ];

    /**
     * Mendapatkan atribut yang harus di-cast (diubah tipe datanya).
     * Misal email_verified_at di-cast ke datetime, password ke hashed.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Cast ke objek Carbon/DateTime
            'password' => 'hashed',            // Otomatis hash password saat set
        ];
    }

    /**
     * Mendapatkan role yang dimiliki oleh user.
     * Relasi belongsTo ke model Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Mendapatkan peminjaman yang dilakukan oleh user ini.
     * Relasi hasMany ke model Borrowing.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
