<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim')->primary();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('prodi');
            $table->enum('gedung', ['tb1', 'tb2', 'tb3', 'tb4', 'tb5']);
            $table->string('no_kamar');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('asal');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('golongan_ukt', ['1', '2', '3', '4', '5', '6', '7', '8']);
            $table->enum('status', ['Aktif Tinggal', 'Tidak Aktif'])->default('Aktif Tinggal');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            // Unique constraints
            $table->unique(['gedung', 'no_kamar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};