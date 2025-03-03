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
        'terisi',
        'keterangan',
        'created_by',
        'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($kamar) {
            if ($kamar->terisi > $kamar->kapasitas) {
                throw new \Exception("Jumlah terisi tidak boleh melebihi kapasitas kamar");
            }
            
            // Update status otomatis berdasarkan perbandingan terisi dan kapasitas
            if ($kamar->terisi >= $kamar->kapasitas) {
                $kamar->status = 'terisi';
            } elseif ($kamar->terisi == 0) {
                $kamar->status = 'tersedia';
            }
            
            return true;
        });
    }

    // Relasi ke mahasiswa yang menempati
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'kamar_id', 'gedung');
    }

    // Relasi ke kasra yang mengelola
    public function kasra()
    {
        return $this->belongsTo(Kasra::class, 'kamar_id', 'gedung');
    }
}