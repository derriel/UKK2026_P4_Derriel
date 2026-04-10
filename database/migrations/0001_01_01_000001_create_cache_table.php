<?php

// Import kelas yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk membuat tabel cache dan cache_locks
return new class extends Migration
{
    /**
     * Run the migrations.
     * Menyediakan tabel untuk mekanisme caching internal Laravel.
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();              // Kunci cache unik
            $table->mediumText('value');                   // Isi cache
            $table->integer('expiration');                 // Waktu kadaluarsa cache
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();              // Kunci lock cache unik
            $table->string('owner');                       // Identitas pemilik lock
            $table->integer('expiration');                 // Waktu kadaluarsa lock
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel cache saat rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
