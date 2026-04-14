<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    protected $fillable = [
        'name',
        'grade',
        'jurusan',
        'description',
        'capacity',
    ];

    /**
     * Relasi ke model Siswa (siswa yang berada di kelas ini)
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'class_room_id');
    }

    /**
     * Hitung jumlah siswa aktif di kelas ini
     */
    public function getActiveStudentsCountAttribute()
    {
        return $this->siswa()->whereHas('user', function($query) {
            $query->where('role_id', function($subQuery) {
                $subQuery->select('id')
                    ->from('roles')
                    ->where('name', 'member')
                    ->limit(1);
            });
        })->count();
    }
}
