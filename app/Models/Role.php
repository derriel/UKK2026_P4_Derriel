<?php

// Namespace untuk model
namespace App\Models;

// Import model base
use Illuminate\Database\Eloquent\Model;

// Model Role yang mewakili tabel roles di database
class Role extends Model
{
    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'name',        // Nama role (misal: admin, user)
        'description', // Deskripsi role
    ];

    /**
     * Mendapatkan users yang memiliki role ini.
     * Relasi hasMany ke model User.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
