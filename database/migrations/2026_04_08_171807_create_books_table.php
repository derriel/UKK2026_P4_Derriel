<?php

// Import class yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk membuat tabel books
return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel books untuk menyimpan data buku perpustakaan
     */
    public function up(): void
    {
        // Membuat tabel books dengan kolom-kolom berikut:
        Schema::create('books', function (Blueprint $table) {
            $table->id();                                    // Primary key auto-increment
            $table->string('title');                          // Judul buku
            $table->string('author');                         // Nama penulis
            $table->string('publisher')->nullable();          // Penerbit (opsional)
            $table->string('isbn')->unique()->nullable();     // ISBN unik (opsional)
            $table->integer('stock')->default(0);             // Jumlah stok buku (default 0)
            $table->text('description')->nullable();           // Deskripsi buku (opsional)
            $table->string('category')->nullable();            // Kategori buku (opsional)
            $table->year('publication_year')->nullable();      // Tahun terbit (opsional)
            $table->timestamps();                             // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel books jika migration di-rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
