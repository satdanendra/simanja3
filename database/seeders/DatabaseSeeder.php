<?php

namespace Database\Seeders;

use App\Models\MasterPegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles terlebih dahulu
        $this->call(RolesSeeder::class);

        // Buat data pegawai dummy untuk test user
        $testPegawai = MasterPegawai::create([
            'nama_lengkap' => 'Test User',
            'jenis_kelamin' => 'L',
            'gelar' => ', S.Kom.',
            'alias' => 'Admin',
            'nip_lama' => '123456789',
            'nip_baru' => '199001012020121001',
            'nik' => '1234567890123456',
            'email' => 'test@example.com',
            'nomor_hp' => '081234567890',
            'pangkat' => '4A',
            'jabatan' => 'Administrator Sistem',
            'pendidikan_tertinggi' => 'S1',
            'program_studi' => 'Sistem Informasi',
            'universitas' => 'Universitas Test',
        ]);

        // Buat user test dengan relasi ke pegawai
        $testUser = User::create([
            'name' => $testPegawai->nama_lengkap,
            'email' => $testPegawai->email,
            'password' => Hash::make($testPegawai->nip_lama), // password dari NIP lama
            'pegawai_id' => $testPegawai->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Assign role superadmin ke test user
        $testUser->assignRole(Role::SUPERADMIN);

        // Buat beberapa pegawai dummy lainnya untuk testing
        $dummyPegawais = [
            [
                'nama_lengkap' => 'John Doe',
                'jenis_kelamin' => 'L',
                'gelar' => ', S.ST.',
                'alias' => 'John',
                'nip_lama' => '987654321',
                'nip_baru' => '199002022020121002',
                'email' => 'john@bps.go.id',
                'jabatan' => 'Statistisi',
            ],
            [
                'nama_lengkap' => 'Jane Smith',
                'jenis_kelamin' => 'P',
                'gelar' => ', S.Si.',
                'alias' => 'Jane',
                'nip_lama' => '456789123',
                'nip_baru' => '199003032020122001',
                'email' => 'jane@bps.go.id',
                'jabatan' => 'Analis Data',
            ],
        ];

        foreach ($dummyPegawais as $pegawaiData) {
            $pegawai = MasterPegawai::create($pegawaiData);
            
            // Auto create user untuk setiap pegawai
            $user = User::create([
                'name' => $pegawai->nama_lengkap,
                'email' => $pegawai->email,
                'password' => Hash::make($pegawai->nip_lama),
                'pegawai_id' => $pegawai->id,
                'is_active' => true,
            ]);

            // Assign role default anggota_tim
            $user->assignRole(Role::ANGGOTA_TIM);
        }
    }
}