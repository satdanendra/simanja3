<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('gelar')->nullable();
            $table->string('alias')->nullable();
            $table->string('nip_lama');
            $table->string('nip_baru')->nullable();
            $table->string('nik')->nullable();
            $table->string('email')->unique();
            $table->string('nomor_hp')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('pendidikan_tertinggi')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('universitas')->nullable();
            $table->timestamps();
            $table->softDeletes(); // untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pegawais');
    }
};