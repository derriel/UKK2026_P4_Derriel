<?php

/**
 * Model Fine
 * Mewakili tabel fines di database
 * Berisi data denda keterlambatan peminjaman buku
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'borrowing_id',  // ID peminjaman
        'amount',        // Jumlah denda
        'status',        // Status: unpaid/paid
        'paid_at',       // Tanggal pembayaran
        'notes',        // Catatan
    ];

    // Casting tipe data
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Relasi ke model Borrowing
     */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
