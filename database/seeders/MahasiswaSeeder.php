<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Kamar;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        // Buat user terlebih dahulu
        $user1 = User::create([
            'username' => 'john_doe',
            'email' => 'john@student.com',
            'password' => Hash::make('mhs123'),
            'role' => 'mahasiswa'
        ]);
        
        $user2 = User::create([
            'username' => 'jane_doe',
            'email' => 'jane@student.com',
            'password' => Hash::make('mhs123'),
            'role' => 'mahasiswa'
        ]);
        
        // Pastikan kamar sudah ada
        $kamarA101 = Kamar::where('gedung', 'tb1')->where('no_kamar', 'A101')->first();
        $kamarB202 = Kamar::where('gedung', 'tb4')->where('no_kamar', 'B202')->first();
        
        if (!$kamarA101) {
            $kamarA101 = Kamar::create([
                'gedung' => 'tb1',
                'no_kamar' => 'A101',
                'lantai' => 1,
                'status' => 'terisi',
                'kapasitas' => 2,
                'terisi' => 1
            ]);
        }
        
        if (!$kamarB202) {
            $kamarB202 = Kamar::create([
                'gedung' => 'tb4',
                'no_kamar' => 'B202',
                'lantai' => 2,
                'status' => 'terisi',
                'kapasitas' => 2,
                'terisi' => 1
            ]);
        }

        // Create sample mahasiswa
        Mahasiswa::create([
            'nim' => '12345678',
            'nama' => 'John Doe',
            'prodi' => 'Informatika',
            'kamar_id' => $kamarA101->id,
            'email' => 'john@student.com',
            'tanggal_lahir' => '2000-01-01',
            'tempat_lahir' => 'Jakarta',
            'asal' => 'Jakarta',
            'status' => 'Aktif Tinggal',
            'golongan_ukt' => '7',
            'jenis_kelamin' => 'Laki-laki',
            'user_id' => $user1->id,
            'created_by' => 1
        ]);

        Mahasiswa::create([
            'nim' => '87654321',
            'nama' => 'Jane Doe',
            'prodi' => 'Elektro',
            'kamar_id' => $kamarB202->id,
            'email' => 'jane@student.com',
            'tanggal_lahir' => '2000-02-02',
            'tempat_lahir' => 'Surabaya',
            'asal' => 'Surabaya',
            'status' => 'Aktif Tinggal',
            'golongan_ukt' => '5',
            'jenis_kelamin' => 'Perempuan',
            'user_id' => $user2->id,
            'created_by' => 1
        ]);
    }
}