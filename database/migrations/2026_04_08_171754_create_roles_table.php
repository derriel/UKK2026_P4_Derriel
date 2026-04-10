<?php

// Import class yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk membuat tabel roles
return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel roles untuk menyimpan data peran pengguna
     */
    public function up(): void
    {
        // Membuat tabel roles dengan kolom-kolom berikut:
        Schema::create('roles', function (Blueprint $table) {
            $table->id();                              // Primary key auto-increment
            $table->string('name')->unique();           // Nama role (admin, librarian, member) - harus unik
            $table->string('description')->nullable();  // Deskripsi role (opsional)
            $table->timestamps();                       // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel roles jika migration di-rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
