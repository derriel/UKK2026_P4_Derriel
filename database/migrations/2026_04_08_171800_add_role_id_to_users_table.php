<?php

// Import kelas yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk menambahkan relasi role pada users
return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom role_id ke tabel users untuk hubungan role.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom role_id dan constraint saat rollback.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
