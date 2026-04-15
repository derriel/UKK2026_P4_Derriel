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
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('title');
            }

            if (!Schema::hasColumn('books', 'publisher_id')) {
                $table->unsignedBigInteger('publisher_id')->nullable()->after('author_id');
            }
        });

        if (!Schema::hasColumn('books', 'author_id')) {
            Schema::table('books', function (Blueprint $table) {
                $table->foreign('author_id')->references('id')->on('authors')->onDelete('set null');
            });
        }

        if (!Schema::hasColumn('books', 'publisher_id')) {
            Schema::table('books', function (Blueprint $table) {
                $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'publisher_id')) {
                $table->dropColumn('publisher_id');
            }
            if (Schema::hasColumn('books', 'author_id')) {
                $table->dropColumn('author_id');
            }
        });
    }
};
