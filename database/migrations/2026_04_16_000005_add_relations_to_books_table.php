<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'author')) {
                $table->dropColumn('author');
            }
            if (Schema::hasColumn('books', 'publisher')) {
                $table->dropColumn('publisher');
            }
            if (Schema::hasColumn('books', 'category')) {
                $table->dropColumn('category');
            }
        });

        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('author_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');
            
            $table->foreignId('publisher_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');
            
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');
            
            $table->integer('fine_per_day')
                  ->default(5000)
                  ->nullable();
            
            $table->boolean('is_active')
                  ->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['publisher_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['author_id', 'publisher_id', 'category_id', 'fine_per_day', 'is_active']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('category')->nullable();
        });
    }
};
