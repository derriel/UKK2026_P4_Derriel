<?php

// Namespace untuk seeder database
namespace Database\Seeders;

// Import class yang digunakan
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Class seeder untuk mengisi data tabel books
class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array data buku yang akan di-insert ke tabel books
        $books = [
            [
                'title' => 'Belajar Laravel untuk Pemula',        // Judul buku
                'author' => 'John Doe',                         // Nama penulis
                'publisher' => 'Tech Publisher',                // Nama penerbit
                'isbn' => '9781234567890',                      // Nomor ISBN
                'stock' => 5,                                   // Jumlah stok buku
                'description' => 'Buku panduan lengkap untuk belajar framework Laravel', // Deskripsi buku
                'category' => 'Programming',                    // Kategori buku
                'publication_year' => 2023                      // Tahun terbit
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

        // Loop untuk insert setiap buku ke database
        foreach ($books as $book) {
            \DB::table('books')->insert($book);
        }
    }
}
