<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran';

    protected $fillable = [
        'nim',
        'tanggal_pelanggaran',
        'keterangan_pelanggaran',
        'created_by'
    ];

    protected $casts = [
        'tanggal_pelanggaran' => 'date'
    ];

    /**
     * Get the mahasiswa that owns the pelanggaran
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    /**
     * Get the user that created the pelanggaran
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}