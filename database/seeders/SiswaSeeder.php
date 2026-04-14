<?php

// Namespace untuk seeder database
namespace Database\Seeders;

// Import class yang digunakan
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Class seeder untuk mengisi data tabel siswa
class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array data siswa yang akan di-insert ke tabel siswa
        $siswa = [
            [
                'id_siswa' => 'SIS001',                        // ID siswa unik
                'nis' => '3273081203000001',                   // Nomor Induk Siswa
                'name' => 'Ahmad Rahman',                      // Nama lengkap siswa
                'email' => 'ahmad@example.com',                // Email siswa
                'phone' => '081234567890',                     // Nomor telepon
                'address' => 'Jl. Sudirman No. 123, Jakarta',  // Alamat lengkap
                'kelas' => 'XII',                              // Kelas siswa
                'jurusan' => 'RPL',                            // Jurusan siswa
                'birth_date' => '1995-05-15',                  // Tanggal lahir
                'gender' => 'male',                            // Jenis kelamin
                'join_date' => '2024-01-15',                   // Tanggal bergabung
                'status' => 'active'                           // Status keanggotaan
            ],
            [
                'id_siswa' => 'SIS002',
                'nis' => '3275022404000002',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'phone' => '081987654321',
                'address' => 'Jl. Thamrin No. 456, Jakarta',
                'kelas' => 'XI',
                'jurusan' => 'TKJ',
                'birth_date' => '1998-08-22',
                'gender' => 'female',
                'join_date' => '2024-02-10',
                'status' => 'active'
            ],
            [
                'id_siswa' => 'SIS003',
                'nis' => '3272051705000003',
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081345678901',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
                'kelas' => 'X',
                'jurusan' => 'MM',
                'birth_date' => '2000-12-05',
                'gender' => 'male',
                'join_date' => '2024-03-01',
                'status' => 'active'
            ],
            [
                'id_siswa' => 'SIS004',
                'nis' => '3271061806000004',
                'name' => 'Maya Sari',
                'email' => 'maya@example.com',
                'phone' => '081456789012',
                'address' => 'Jl. MH Thamrin No. 321, Jakarta',
                'kelas' => 'XII',
                'jurusan' => 'AK',
                'birth_date' => '1997-11-18',
                'gender' => 'female',
                'join_date' => '2024-01-20',
                'status' => 'active'
            ],
            [
                'id_siswa' => 'SIS005',
                'nis' => '3274073007000005',
                'name' => 'Rudi Hartono',
                'email' => 'rudi@example.com',
                'phone' => '081567890123',
                'address' => 'Jl. Jendral Sudirman No. 654, Jakarta',
                'kelas' => 'XI',
                'jurusan' => 'RPL',
                'birth_date' => '1990-07-30',
                'gender' => 'male',
                'join_date' => '2024-02-28',
                'status' => 'inactive'
            ]
        ];

        // Loop untuk insert setiap siswa ke database
        foreach ($siswa as $siswaData) {
            \DB::table('siswa')->insert($siswaData);
        }
    }
}
