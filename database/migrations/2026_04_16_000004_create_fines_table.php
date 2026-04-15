<?php

/**
 * Migration untuk membuat tabel fines
 * Tabel ini menyimpan data denda keterlambatan peminjaman buku
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel fines dengan kolom-kolom berikut:
     */
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();                             // Primary key auto-increment
            $table->foreignId('borrowing_id')         // foreign key ke tabel borrowings
                  ->constrained()
                  ->onDelete('cascade');
            $table->decimal('amount', 10, 2);        // Jumlah denda
            $table->enum('status', ['unpaid', 'paid']) // Status pembayaran
                  ->default('unpaid');
            $table->timestamp('paid_at')->nullable(); // Tanggal pembayaran (opsional)
            $table->text('notes')->nullable();     // Catatan (opsional)
            $table->timestamps();                  // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel fines jika migration di-rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};