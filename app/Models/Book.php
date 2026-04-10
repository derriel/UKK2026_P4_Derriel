<?php

// Namespace untuk model
namespace App\Models;

// Import model base
use Illuminate\Database\Eloquent\Model;

// Model Book yang mewakili tabel books di database
class Book extends Model
{
    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'title',           // Judul buku
        'author',          // Penulis buku
        'publisher',       // Penerbit buku
        'isbn',            // ISBN buku
        'stock',           // Stok buku
        'description',     // Deskripsi buku
        'category',        // Kategori buku
        'publication_year', // Tahun terbit
    ];

    /**
     * Mendapatkan peminjaman yang terkait dengan buku ini.
     * Relasi hasMany ke model Borrowing.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
