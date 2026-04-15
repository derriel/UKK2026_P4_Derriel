<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek apakah tabel members ada dan kolom belum ada
        if (Schema::hasTable('members') && !Schema::hasColumn('members', 'identity_number')) {
            Schema::table('members', function (Blueprint $table) {
                $table->string('identity_number')->unique()->nullable();
            });
        }
        
        // Cek apakah tabel siswa ada dan kolom belum ada
        if (Schema::hasTable('siswa') && !Schema::hasColumn('siswa', 'identity_number')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('identity_number')->unique()->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('identity_number');
        });
    }
};