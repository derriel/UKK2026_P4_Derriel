<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'name',
        'email',
        'birth_date',
        'nationality',
        'biography',
    ];

    // relasi ke buku
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
