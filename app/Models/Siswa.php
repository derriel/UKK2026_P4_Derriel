<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'class_room_id',
        'join_date',
        'status',
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function setAttribute($key, $value)
    {
        if ($key === 'nis' && !Schema::hasColumn('siswa', 'nis')) {
            return $this;
        }
        if ($key === 'id_siswa' && !Schema::hasColumn('siswa', 'id_siswa')) {
            return $this;
        }
        return parent::setAttribute($key, $value);
    }
}
