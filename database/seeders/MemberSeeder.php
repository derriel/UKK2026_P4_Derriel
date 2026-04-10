<?php

// Namespace untuk seeder database
namespace Database\Seeders;

// Import class yang digunakan
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Class seeder untuk mengisi data tabel members
class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array data anggota yang akan di-insert ke tabel members
        $members = [
            [
                'member_number' => 'MEM001',                    // Nomor anggota unik
                'identity_number' => '3273081203000001',        // Nomor identitas
                'name' => 'Ahmad Rahman',                       // Nama lengkap anggota
                'email' => 'ahmad@example.com',                 // Email anggota
                'phone' => '081234567890',                      // Nomor telepon
                'address' => 'Jl. Sudirman No. 123, Jakarta',   // Alamat lengkap
                'birth_date' => '1995-05-15',                  // Tanggal lahir
                'gender' => 'male',                            // Jenis kelamin
                'join_date' => '2024-01-15',                   // Tanggal bergabung
                'status' => 'active'                           // Status keanggotaan
            ],
            [
                'member_number' => 'MEM002',
                'identity_number' => '3275022404000002',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'phone' => '081987654321',
                'address' => 'Jl. Thamrin No. 456, Jakarta',
                'birth_date' => '1998-08-22',
                'gender' => 'female',
                'join_date' => '2024-02-10',
                'status' => 'active'
            ],
            [
                'member_number' => 'MEM003',
                'identity_number' => '3272051705000003',
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081345678901',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
                'birth_date' => '1992-12-03',
                'gender' => 'male',
                'join_date' => '2024-03-05',
                'status' => 'active'
            ],
            [
                'member_number' => 'MEM004',
                'identity_number' => '3271061806000004',
                'name' => 'Maya Sari',
                'email' => 'maya@example.com',
                'phone' => '081456789012',
                'address' => 'Jl. MH Thamrin No. 321, Jakarta',
                'birth_date' => '1997-11-18',
                'gender' => 'female',
                'join_date' => '2024-01-20',
                'status' => 'active'
            ],
            [
                'member_number' => 'MEM005',
                'identity_number' => '3274073007000005',
                'name' => 'Rudi Hartono',
                'email' => 'rudi@example.com',
                'phone' => '081567890123',
                'address' => 'Jl. Jendral Sudirman No. 654, Jakarta',
                'birth_date' => '1990-07-30',
                'gender' => 'male',
                'join_date' => '2024-02-28',
                'status' => 'inactive'  // Status tidak aktif
            ]
        ];

        // Loop untuk insert setiap anggota ke database
        foreach ($members as $member) {
            \DB::table('members')->insert($member);
        }
    }
}
