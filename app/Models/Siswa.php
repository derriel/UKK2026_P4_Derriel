<?php

// Namespace untuk model
namespace App\Models;

// Import model base
use Illuminate\Database\Eloquent\Model;

// Model Siswa yang mewakili tabel siswa di database
class Siswa extends Model
{
    // Nama tabel yang digunakan
    protected $table = 'siswa';

    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'id_siswa', // ID siswa
        'nis',      // Nomor Induk Siswa
        'name',     // Nama siswa
        'email',    // Email siswa
        'phone',    // Nomor telepon
        'address',  // Alamat
        'kelas',    // Kelas siswa
        'jurusan',  // Jurusan siswa
        'birth_date', // Tanggal lahir
        'gender',   // Jenis kelamin
        'join_date', // Tanggal bergabung
        'status',   // Status siswa (aktif/nonaktif)
        'class_room_id', // ID kelas
    ];

    /**
     * Mendapatkan peminjaman yang terkait dengan siswa ini.
     * Relasi hasMany ke model Borrowing.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Mendapatkan kelas yang terkait dengan siswa ini.
     * Relasi belongsTo ke model ClassRoom.
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    /**
     * Mendapatkan user yang terkait dengan siswa ini.
     * Relasi belongsTo ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
