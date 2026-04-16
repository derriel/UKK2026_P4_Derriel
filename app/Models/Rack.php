<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;
use App\Models\Category;

class Rack extends Model
{
    protected $fillable = [
        'name',
        'location',
        'category_id',
        'description',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}