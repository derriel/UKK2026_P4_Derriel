<?php

// Namespace untuk seeder database
namespace Database\Seeders;

// Import model dan class yang digunakan
use App\Models\User;
use Database\Seeders\SiswaSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Class utama untuk seeding database
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil seeder lainnya untuk mengisi data awal
        $this->call([
            RoleSeeder::class,    // Seeder untuk tabel roles
            ClassRoomSeeder::class, // Seeder untuk tabel class_rooms
            BookSeeder::class,    // Seeder untuk tabel books
            SiswaSeeder::class,  // Seeder untuk tabel siswa
        ]);

        // Membuat user admin dengan role admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@perpustakaan.com',
            'password' => bcrypt('password123'), // Password di-hash menggunakan bcrypt
            'role_id' => 1, // ID role admin
        ]);

        // Membuat user pustakawan dengan role librarian
        User::factory()->create([
            'name' => 'Librarian User',
            'email' => 'librarian@perpustakaan.com',
            'password' => bcrypt('password123'),
            'role_id' => 2, // ID role librarian
        ]);

        // Membuat user anggota biasa dengan role member
        User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@perpustakaan.com',
            'password' => bcrypt('password123'),
            'role_id' => 3, // ID role member
        ]);
    }
}
