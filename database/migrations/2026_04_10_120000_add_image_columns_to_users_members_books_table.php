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
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('remember_token');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('address');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('photo');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('cover_image');
        });
    }
};
