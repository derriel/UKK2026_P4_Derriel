<?php

// Namespace untuk model
namespace App\Models;

// Import model base
use Illuminate\Database\Eloquent\Model;

// Model Member yang mewakili tabel members di database
class Member extends Model
{
    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'member_number', // Nomor anggota
        'name',          // Nama anggota
        'email',         // Email anggota
        'phone',         // Nomor telepon
        'address',       // Alamat
        'birth_date',    // Tanggal lahir
        'gender',        // Jenis kelamin
        'join_date',     // Tanggal bergabung
        'status',        // Status anggota (aktif/nonaktif)
        'photo',         // Foto anggota
    ];

    /**
     * Mendapatkan peminjaman yang terkait dengan anggota ini.
     * Relasi hasMany ke model Borrowing.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
