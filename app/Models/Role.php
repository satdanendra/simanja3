<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Users yang memiliki role ini
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')
                    ->withPivot('tim_id')
                    ->withTimestamps();
    }

    /**
     * Konstanta untuk nama role
     */
    public const SUPERADMIN = 'superadmin';
    public const KEPALA_BPS = 'kepala_bps';
    public const KETUA_TIM = 'ketua_tim';
    public const PIC_PROYEK = 'pic_proyek';
    public const ANGGOTA_TIM = 'anggota_tim';

    /**
     * Daftar semua role yang tersedia
     */
    public static function getAllRoles(): array
    {
        return [
            self::SUPERADMIN,
            self::KEPALA_BPS,
            self::KETUA_TIM,
            self::PIC_PROYEK,
            self::ANGGOTA_TIM,
        ];
    }
}