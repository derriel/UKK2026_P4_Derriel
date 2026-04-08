<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            BookSeeder::class,
            MemberSeeder::class,
        ]);

        // Create admin user with role
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@perpustakaan.com',
            'password' => bcrypt('password123'),
            'role_id' => 1, // admin role
        ]);

        // Create librarian user
        User::factory()->create([
            'name' => 'Librarian User',
            'email' => 'librarian@perpustakaan.com',
            'password' => bcrypt('password123'),
            'role_id' => 2, // librarian role
        ]);

        // Create regular member user
        User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@perpustakaan.com',
            'password' => bcrypt('password123'),
            'role_id' => 3, // member role
        ]);
    }
}
