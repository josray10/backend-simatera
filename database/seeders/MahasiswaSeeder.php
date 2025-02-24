<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        // Create sample mahasiswa
        Mahasiswa::create([
            'id' => 1,
            'nim' => '12345678',
            'nama' => 'John Doe',
            'prodi' => 'Informatika',
            'gedung' => 'tb1',
            'no_kamar' => 'A101',
            'email' => 'john@student.com',
            'tanggal_lahir' => '2000-01-01',
            'tempat_lahir' => 'Jakarta',
            'asal' => 'Jakarta',
            'status' => 'Aktif Tinggal',
            'golongan_ukt' => '7',
            'gender' => 'Laki-laki',
            'password' => Hash::make('mhs123'),
            'created_by' => 1
        ]);

        Mahasiswa::create([
            'id' => 2,
            'nim' => '87654321',
            'nama' => 'Jane Doe',
            'prodi' => 'Elektro',
            'gedung' => 'tb4',
            'no_kamar' => 'B202',
            'email' => 'jane@student.com',
            'tanggal_lahir' => '2000-02-02',
            'tempat_lahir' => 'Surabaya',
            'asal' => 'Surabaya',
            'status' => 'Aktif Tinggal',
            'golongan_ukt' => '5',
            'gender' => 'Perempuan',
            'password' => Hash::make('mhs123'),
            'created_by' => 1
        ]);
    }
}