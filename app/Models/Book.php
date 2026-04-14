<?php

// Namespace untuk model
namespace App\Models;

// Import model base
use Illuminate\Database\Eloquent\Model;
use App\Models\Author;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Publisher;

// Model Book yang mewakili tabel books di database
class Book extends Model
{
    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'title',           // Judul buku
        'author_id',          // Penulis buku
        'publisher_id',       // Penerbit buku
        'category_id',      // Kategori buku
        'isbn',            // ISBN buku
        'stock',           // Stok buku
        'description',     // Deskripsi buku
        'category',        // Kategori buku lama (fallback)
        'publication_year', // Tahun terbit
        'cover_image',     // Gambar sampul buku
    ];

    /**
     * Mendapatkan peminjaman yang terkait dengan buku ini.
     * Relasi hasMany ke model Borrowing.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
