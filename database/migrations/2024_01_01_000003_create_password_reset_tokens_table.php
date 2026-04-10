<?php

// Import kelas yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk membuat tabel password_reset_tokens
return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel untuk menyimpan token reset password.
     */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();           // Email sebagai identitas pengguna
            $table->string('token');                      // Token reset password yang di-generate
            $table->timestamp('created_at')->nullable();  // Waktu pembuatan token
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel password_reset_tokens jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
