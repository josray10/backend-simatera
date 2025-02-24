<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'nim';
    protected $keyType = 'string';
    public $incrementing = false;

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
        'user_id'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke kamar
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, ['gedung', 'no_kamar'], ['gedung', 'no_kamar']);
    }

    // Relasi ke pelanggaran
    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'nim', 'nim');
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'nim', 'nim');
    }

    // Relasi ke pengaduan
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'nim', 'nim');
    }
}