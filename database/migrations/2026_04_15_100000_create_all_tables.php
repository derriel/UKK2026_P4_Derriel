<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('borrowings');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('books');
        Schema::dropIfExists('racks');
        Schema::dropIfExists('class_rooms');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->string('photo')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->date('birth_date')->nullable();
            $table->string('nationality')->nullable();
            $table->text('biography')->nullable();
            $table->timestamps();
        });

        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('racks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('grade')->nullable();
            $table->string('jurusan')->nullable();
            $table->text('description')->nullable();
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('author_id')->nullable()->constrained('authors')->onDelete('set null');
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('rack_id')->nullable()->constrained('racks')->onDelete('set null');
            $table->string('isbn')->unique()->nullable();
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });

        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('kelas')->nullable();
            $table->string('jurusan')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('join_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('class_room_id')->nullable()->constrained('class_rooms')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'overdue', 'requested', 'return_requested', 'approved', 'rejected'])->default('borrowed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('books');
        Schema::dropIfExists('racks');
        Schema::dropIfExists('class_rooms');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
