<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'role_id',
        'borrow_date',
        'due_date',
        'return_date',
        'returned_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'returned_at' => 'datetime',
    ];

    /**
     * Get the user that owns the borrowing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that is borrowed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the role associated with the borrowing.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
