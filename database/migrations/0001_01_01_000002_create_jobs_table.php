<?php

// Import kelas yang diperlukan untuk migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration untuk membuat tabel queue yang digunakan oleh Laravel jobs
return new class extends Migration
{
    /**
     * Run the migrations.
     * Menyediakan tabel untuk job queue, batch job, dan failed jobs.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();                                 // Primary key auto-increment
            $table->string('queue')->index();             // Nama queue
            $table->longText('payload');                  // Payload job yang diserialisasi
            $table->unsignedTinyInteger('attempts');      // Jumlah percobaan eksekusi
            $table->unsignedInteger('reserved_at')->nullable(); // Timestamp job dipesan
            $table->unsignedInteger('available_at');      // Timestamp job siap diproses
            $table->unsignedInteger('created_at');        // Timestamp dibuat
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();              // ID batch unik
            $table->string('name');                       // Nama batch
            $table->integer('total_jobs');                // Total job dalam batch
            $table->integer('pending_jobs');              // Job yang masih menunggu
            $table->integer('failed_jobs');               // Job yang gagal
            $table->longText('failed_job_ids');           // ID job yang gagal
            $table->mediumText('options')->nullable();    // Opsi batch (opsional)
            $table->integer('cancelled_at')->nullable();  // Timestamp batal batch
            $table->integer('created_at');                // Timestamp batch dibuat
            $table->integer('finished_at')->nullable();   // Timestamp selesai batch
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();                                 // Primary key auto-increment
            $table->string('uuid')->unique();             // UUID unik untuk entri gagal
            $table->text('connection');                   // Koneksi queue
            $table->text('queue');                        // Nama queue
            $table->longText('payload');                  // Payload job asli
            $table->longText('exception');                // Exception atau kesalahan yang terjadi
            $table->timestamp('failed_at')->useCurrent(); // Waktu gagal
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel job saat rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
