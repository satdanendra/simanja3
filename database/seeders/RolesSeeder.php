<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => Role::SUPERADMIN],
            ['name' => Role::KEPALA_BPS],
            ['name' => Role::KETUA_TIM],
            ['name' => Role::PIC_PROYEK],
            ['name' => Role::ANGGOTA_TIM],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}