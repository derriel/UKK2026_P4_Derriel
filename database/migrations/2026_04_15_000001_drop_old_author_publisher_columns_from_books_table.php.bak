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
        if (Schema::hasColumn('books', 'author')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('author');
            });
        }

        if (Schema::hasColumn('books', 'publisher')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('publisher');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'author')) {
                $table->string('author')->nullable()->after('author_id');
            }
            if (!Schema::hasColumn('books', 'publisher')) {
                $table->string('publisher')->nullable()->after('author');
            }
        });
    }
};
