<?php

// Import class yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk menambahkan kolom returned_at pada tabel borrowings
return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom returned_at untuk mencatat waktu pengembalian buku
     */
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->timestamp('returned_at')->nullable()->after('return_date'); // Waktu pengembalian riil
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom returned_at jika migration di-rollback
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('returned_at');
        });
    }
};
