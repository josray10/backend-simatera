<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kasra', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('prodi');
            $table->enum('gedung', ['tb1', 'tb2', 'tb3', 'tb4', 'tb5']);
            $table->string('no_kamar');
            $table->string('email');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('asal');
            $table->enum('status', ['Aktif Tinggal', 'Checkout']);
            $table->string('golongan_ukt');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('password');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasra');
    }
};
