<?php

// Namespace untuk model
namespace App\Models;

// Import model base
use Illuminate\Database\Eloquent\Model;

// Model Borrowing yang mewakili tabel borrowings di database
class Borrowing extends Model
{
    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'user_id',     // ID pengguna yang meminjam
        'book_id',     // ID buku yang dipinjam
        'role_id',     // ID role pengguna
        'borrow_date', // Tanggal peminjaman
        'due_date',    // Tanggal jatuh tempo
        'return_date', // Tanggal pengembalian (direncanakan)
        'returned_at', // Tanggal aktual pengembalian
        'status',      // Status peminjaman (misal: borrowed, returned)
        'notes',       // Catatan tambahan
        'fine',        // Denda keterlambatan
        'fine_status', // Status denda (unpaid/paid)
        'paid_at',     // Tanggal pembayaran denda
    ];

    // Cast atribut ke tipe data tertentu
    protected $casts = [
        'borrow_date' => 'date',     // Cast ke objek date
        'due_date' => 'date',        // Cast ke objek date
        'return_date' => 'date',     // Cast ke objek date
        'returned_at' => 'datetime', // Cast ke objek datetime
        'fine' => 'decimal:2',      // Cast fine ke decimal
        'paid_at' => 'datetime',    // Cast paid_at ke datetime
    ];

    /**
     * Mendapatkan user yang melakukan peminjaman.
     * Relasi belongsTo ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan buku yang dipinjam.
     * Relasi belongsTo ke model Book.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Mendapatkan role yang terkait dengan peminjaman.
     * Relasi belongsTo ke model Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
