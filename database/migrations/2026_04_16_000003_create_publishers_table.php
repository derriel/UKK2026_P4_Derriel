<?php

/**
 * Migration untuk membuat tabel publishers
 * Tabel ini menyimpan data penerbit buku perpustakaan
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel publishers dengan kolom-kolom berikut:
     */
    public function up(): void
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel publishers jika migration di-rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('publishers');
    }
};