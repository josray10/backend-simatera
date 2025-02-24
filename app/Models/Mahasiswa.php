<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';        // Set primary key ke nim
    public $incrementing = false;         // Disable auto-increment
    protected $keyType = 'string';        // Set type ke string

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
        'password'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date'
    ];

    /**
     * Get the user that created the mahasiswa
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get pengaduan for the mahasiswa
     */
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'nim', 'nim');
    }

    /**
     * Get pembayaran for the mahasiswa
     */
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'nim', 'nim');
    }
}