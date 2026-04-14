<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('borrowings')) {
            DB::statement("ALTER TABLE `borrowings` MODIFY `status` ENUM('borrowed','returned','overdue','requested','return_requested') NOT NULL DEFAULT 'borrowed'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('borrowings')) {
            DB::statement("ALTER TABLE `borrowings` MODIFY `status` ENUM('borrowed','returned','overdue') NOT NULL DEFAULT 'borrowed'");
        }
    }
};
