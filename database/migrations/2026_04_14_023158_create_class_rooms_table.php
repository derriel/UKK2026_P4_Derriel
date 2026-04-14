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
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kelas (Kelas 10, Kelas 11, dll)
            $table->string('grade'); // Tingkat kelas (10, 11, 12)
            $table->text('description')->nullable(); // Deskripsi kelas
            $table->integer('capacity')->default(30); // Kapasitas siswa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
