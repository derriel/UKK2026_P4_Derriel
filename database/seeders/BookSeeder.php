<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Belajar Laravel untuk Pemula',
                'author' => 'John Doe',
                'publisher' => 'Tech Publisher',
                'isbn' => '9781234567890',
                'stock' => 5,
                'description' => 'Buku panduan lengkap untuk belajar framework Laravel',
                'category' => 'Programming',
                'publication_year' => 2023
            ],
            [
                'title' => 'Database Design Fundamentals',
                'author' => 'Jane Smith',
                'publisher' => 'Data Books Inc',
                'isbn' => '9780987654321',
                'stock' => 3,
                'description' => 'Panduan dasar perancangan database',
                'category' => 'Database',
                'publication_year' => 2022
            ],
            [
                'title' => 'Web Development Best Practices',
                'author' => 'Bob Johnson',
                'publisher' => 'Web Tech Press',
                'isbn' => '9781122334455',
                'stock' => 7,
                'description' => 'Praktik terbaik dalam pengembangan web',
                'category' => 'Web Development',
                'publication_year' => 2024
            ],
            [
                'title' => 'PHP Advanced Techniques',
                'author' => 'Alice Wilson',
                'publisher' => 'Code Masters',
                'isbn' => '9785566778899',
                'stock' => 4,
                'description' => 'Teknik lanjutan dalam pemrograman PHP',
                'category' => 'Programming',
                'publication_year' => 2023
            ],
            [
                'title' => 'JavaScript Modern Guide',
                'author' => 'Charlie Brown',
                'publisher' => 'JS Publications',
                'isbn' => '9789988776655',
                'stock' => 6,
                'description' => 'Panduan lengkap JavaScript modern',
                'category' => 'Programming',
                'publication_year' => 2024
            ]
        ];

        foreach ($books as $book) {
            \DB::table('books')->insert($book);
        }
    }
}
