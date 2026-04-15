<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClassRoom;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            [
                'name' => 'Kelas 10',
                'grade' => '10',
                'jurusan' => 'Teknik Komputer dan Jaringan',
                'description' => 'Siswa kelas 10',
                'capacity' => 30,
            ],
            [
                'name' => 'Kelas 11',
                'grade' => '11',
                'jurusan' => 'Teknik Komputer dan Jaringan',
                'description' => 'Siswa kelas 11',
                'capacity' => 28,
            ],
            [
                'name' => 'Kelas 12',
                'grade' => '12',
                'jurusan' => 'Teknik Komputer dan Jaringan',
                'description' => 'Siswa kelas 12',
                'capacity' => 25,
            ],
        ];

        foreach ($classes as $class) {
            ClassRoom::create($class);
        }
    }
}
