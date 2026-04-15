<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->decimal('fine', 10, 2)->default(0)->after('notes');
            $table->enum('fine_status', ['unpaid', 'paid'])->default('unpaid')->after('fine');
            $table->date('paid_at')->nullable()->after('fine_status');
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['fine', 'fine_status', 'paid_at']);
        });
    }
};