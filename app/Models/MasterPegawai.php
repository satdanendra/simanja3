<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPegawai extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_lengkap',
        'jenis_kelamin',
        'gelar',
        'alias',
        'nip_lama',
        'nip_baru',
        'nik',
        'email',
        'nomor_hp',
        'pangkat',
        'jabatan',
        'pendidikan_tertinggi',
        'program_studi',
        'universitas',
    ];

    protected $casts = [
        'jenis_kelamin' => 'string',
    ];

    /**
     * User yang terkait dengan pegawai ini
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'pegawai_id');
    }

    /**
     * Scope untuk pencarian berdasarkan nama atau email
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nama_lengkap', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('nip_lama', 'like', "%{$term}%");
        });
    }

    /**
     * Override delete untuk soft delete user juga
     */
    public function delete()
    {
        // Soft delete pegawai
        $result = parent::delete();
        
        // Deactivate user terkait
        if ($this->user) {
            $this->user->update(['is_active' => false]);
            $this->user->delete(); // soft delete user juga
        }
        
        return $result;
    }

    /**
     * Override restore untuk mengaktifkan kembali user
     */
    public function restore()
    {
        $result = parent::restore();
        
        // Reactivate user terkait
        if ($this->user) {
            $this->user->restore();
            $this->user->update(['is_active' => true]);
        }
        
        return $result;
    }
}