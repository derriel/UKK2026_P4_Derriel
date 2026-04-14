<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Rack extends Model
{
    protected $fillable = [
        'name',
        'location',
        'description',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}