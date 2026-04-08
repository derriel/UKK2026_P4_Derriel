<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'member_number' => 'MEM001',
                'name' => 'Ahmad Rahman',
                'email' => 'ahmad@example.com',
                'phone' => '081234567890',
                'address' => 'Jl. Sudirman No. 123, Jakarta',
                'birth_date' => '1995-05-15',
                'gender' => 'male',
                'join_date' => '2024-01-15',
                'status' => 'active'
            ],
            [
                'member_number' => 'MEM002',
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
                'name' => 'Rudi Hartono',
                'email' => 'rudi@example.com',
                'phone' => '081567890123',
                'address' => 'Jl. Jendral Sudirman No. 654, Jakarta',
                'birth_date' => '1990-07-30',
                'gender' => 'male',
                'join_date' => '2024-02-28',
                'status' => 'inactive'
            ]
        ];

        foreach ($members as $member) {
            \DB::table('members')->insert($member);
        }
    }
}
