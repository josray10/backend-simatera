<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'gedung',
        'nomor_kamar',
        'lantai',
        'status_kamar',
        'kapasitas_kamar',
        'terisi'
    ];

    /**
     * Get mahasiswa yang menempati kamar
     */
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'no_kamar', 'nomor_kamar')
                    ->where('gedung', $this->gedung);
    }

    /**
     * Get kasra yang menempati kamar
     */
    public function kasra()
    {
        return $this->hasMany(Kasra::class, 'no_kamar', 'nomor_kamar')
                    ->where('gedung', $this->gedung);
    }

    /**
     * Check if room is full
     */
    public function isFull()
    {
        return $this->terisi >= $this->kapasitas_kamar;
    }

    /**
     * Get available space
     */
    public function getAvailableSpace()
    {
        return $this->kapasitas_kamar - $this->terisi;
    }
}