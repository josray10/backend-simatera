<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasra extends Model
{
    use HasFactory;

    protected $table = 'kasra';

    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'gedung',
        'no_kamar',
        'email',
        'tanggal_lahir',
        'tempat_lahir',
        'asal',
        'status',
        'golongan_ukt',
        'jenis_kelamin',
        'password',
        'created_by'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relationship dengan user yang membuat data
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}