<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kamar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_kamar',
        'gedung',
        'lantai',
        'status',
        'kapasitas',
        'terisi'
    ];

    // Relasi ke mahasiswa yang menempati
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, ['gedung', 'no_kamar'], ['gedung', 'no_kamar']);
    }

    // Relasi ke kasra yang mengelola
    public function kasra()
    {
        return $this->belongsTo(Kasra::class, 'gedung', 'gedung');
    }
}