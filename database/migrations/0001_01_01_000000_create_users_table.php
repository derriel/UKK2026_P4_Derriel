<?php

// Import kelas yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk membuat tabel users, password_reset_tokens, dan sessions
return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat skema awal untuk user authentication dan sesi.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();                                 // Primary key auto-increment
            $table->string('name');                       // Nama pengguna
            $table->string('email')->unique();            // Email unik untuk login
            $table->timestamp('email_verified_at')->nullable(); // Timestamp verifikasi email
            $table->string('password');                   // Hash password
            $table->rememberToken();                      // Token untuk remember-me
            $table->timestamps();                         // created_at dan updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();           // Email adalah primary key untuk token reset
            $table->string('token');                      // Token reset password
            $table->timestamp('created_at')->nullable();  // Waktu pembuatan token
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();              // ID sesi unik
            $table->foreignId('user_id')->nullable()->index(); // User pemilik sesi
            $table->string('ip_address', 45)->nullable(); // Alamat IP sesi
            $table->text('user_agent')->nullable();       // User agent browser/device
            $table->longText('payload');                  // Data sesi terenkripsi
            $table->integer('last_activity')->index();     // Timestamp terakhir aktivitas
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus semua tabel jika migration di-rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
