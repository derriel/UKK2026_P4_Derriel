<?php

// Namespace untuk seeder database
namespace Database\Seeders;

// Import class yang digunakan
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Class seeder untuk mengisi data tabel roles
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array data roles yang akan di-insert ke tabel roles
        $roles = [
            [
                'name' => 'admin',        // Nama role admin
                'description' => 'Administrator with full access'  // Deskripsi role admin
            ],
            [
                'name' => 'librarian',    // Nama role pustakawan
                'description' => 'Librarian with book management access'  // Deskripsi role pustakawan
            ],
            [
                'name' => 'member',       // Nama role anggota
                'description' => 'Regular member with borrowing access'  // Deskripsi role anggota
            ]
        ];

        // Loop untuk insert setiap role ke database
        foreach ($roles as $role) {
            \DB::table('roles')->insert($role);
        }
    }
}
