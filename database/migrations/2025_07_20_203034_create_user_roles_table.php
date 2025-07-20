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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan foreign key ke master_pegawais
            $table->foreignId('pegawai_id')->nullable()->constrained('master_pegawais')->onDelete('set null');
            
            // Tambahkan soft delete untuk users
            $table->softDeletes();
            
            // Tambahkan kolom is_active untuk kontrol akses
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pegawai_id');
            $table->dropSoftDeletes();
            $table->dropColumn('is_active');
        });
    }
};