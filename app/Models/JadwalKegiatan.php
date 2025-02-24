<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKegiatan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kegiatan';

    protected $fillable = [
        'judul_kegiatan',
        'deskripsi_kegiatan',
        'tanggal_kegiatan',
        'file_kegiatan',
        'created_by'
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date'
    ];

    /**
     * Get the user that created the jadwal kegiatan
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}