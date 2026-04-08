<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'isbn',
        'stock',
        'description',
        'category',
        'publication_year',
    ];

    /**
     * Get the borrowings for the book.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
