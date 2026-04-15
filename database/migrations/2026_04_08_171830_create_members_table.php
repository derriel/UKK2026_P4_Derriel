<?php

// Import class yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk membuat tabel members
return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel members untuk menyimpan data anggota perpustakaan
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();                                // Primary key auto-increment
            $table->string('identity_number')->unique()->nullable(); // Nomor identitas (NIK/KTP)
            $table->string('name');                      // Nama anggota
            $table->string('email')->unique();           // Email anggota (unik)
            $table->string('phone')->nullable();         // Nomor telepon (opsional)
            $table->text('address')->nullable();         // Alamat anggota (opsional)
            $table->date('birth_date')->nullable();      // Tanggal lahir (opsional)
            $table->enum('gender', ['male', 'female'])->nullable(); // Jenis kelamin (opsional)
            $table->date('join_date');                   // Tanggal bergabung menjadi anggota
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active'); // Status keanggotaan
            $table->timestamps();                        // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel members jika migration di-rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
