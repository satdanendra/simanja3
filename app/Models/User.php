<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pegawai_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Pegawai yang terkait dengan user ini
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(MasterPegawai::class, 'pegawai_id');
    }

    /**
     * Roles yang dimiliki user ini
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withPivot('tim_id')
                    ->withTimestamps();
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole(string $roleName, int $timId = null): bool
    {
        $query = $this->roles()->where('name', $roleName);
        
        if ($timId !== null) {
            $query->wherePivot('tim_id', $timId);
        }
        
        return $query->exists();
    }

    /**
     * Assign role ke user
     */
    public function assignRole(string $roleName, int $timId = null): bool
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            return false;
        }

        // Cek jika sudah memiliki role ini untuk tim yang sama
        $exists = $this->roles()
                      ->where('role_id', $role->id)
                      ->wherePivot('tim_id', $timId)
                      ->exists();

        if (!$exists) {
            $this->roles()->attach($role->id, ['tim_id' => $timId]);
        }

        return true;
    }

    /**
     * Remove role dari user
     */
    public function removeRole(string $roleName, int $timId = null): bool
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            return false;
        }

        $query = $this->roles()->where('role_id', $role->id);
        
        if ($timId !== null) {
            $query->wherePivot('tim_id', $timId);
        }

        $query->detach();
        return true;
    }

    /**
     * Cek apakah user adalah superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(Role::SUPERADMIN);
    }

    /**
     * Scope untuk user aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Override metode untuk cek apakah bisa login
     */
    public function canLogin(): bool
    {
        return $this->is_active && !$this->trashed();
    }
}