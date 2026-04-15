<?php

// Import class yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk membuat tabel borrowings (peminjaman)
return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel borrowings untuk menyimpan data peminjaman buku
     */
    public function up(): void
    {
        // Membuat tabel borrowings dengan kolom-kolom berikut:
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();                                           // Primary key auto-increment
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');     // Foreign key ke users (akan dihapus jika user dihapus)
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');     // Foreign key ke books (akan dihapus jika book dihapus)
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');     // Foreign key ke roles (akan dihapus jika role dihapus)
            $table->date('borrow_date');                            // Tanggal peminjaman
            $table->date('due_date');                               // Tanggal jatuh tempo pengembalian
            $table->date('return_date')->nullable();                // Tanggal pengembalian (opsional)
            $table->enum('status', ['borrowed', 'returned', 'overdue'])->default('borrowed'); // Status peminjaman
            $table->text('notes')->nullable();                      // Catatan tambahan (opsional)
            $table->timestamps();                                   // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel borrowings jika migration di-rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
