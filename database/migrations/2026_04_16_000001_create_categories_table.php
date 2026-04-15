<?php

/**
 * Migration untuk membuat tabel categories
 * Tabel ini menyimpan data kategori/rak buku perpustakaan
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel categories dengan kolom-kolom berikut:
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();                             // Primary key auto-increment
            $table->string('name');                     // Nama kategori buku
            $table->text('description')->nullable();  // Deskripsi kategori (opsional)
            $table->timestamps();                    // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel categories jika migration di-rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};