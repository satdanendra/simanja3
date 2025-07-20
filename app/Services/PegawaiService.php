<?php

namespace App\Services;

use App\Models\MasterPegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PegawaiService
{
    /**
     * Import pegawai dari data Excel dan auto create user
     */
    public function importPegawai(array $pegawaiData): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'updated' => 0,
            'errors' => [],
            'summary' => []
        ];

        foreach ($pegawaiData as $index => $data) {
            try {
                $result = $this->processSinglePegawai($data, $index + 1);
                
                if ($result['status'] === 'success') {
                    $results['success']++;
                } elseif ($result['status'] === 'updated') {
                    $results['updated']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = $result;
                }
                
                $results['summary'][] = $result;
                
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'row' => $index + 1,
                    'email' => $data['Email'] ?? 'N/A',
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    /**
     * Proses single pegawai data
     */
    private function processSinglePegawai(array $data, int $rowNumber): array
    {
        // Validasi data
        $validator = $this->validatePegawaiData($data);
        
        if ($validator->fails()) {
            return [
                'row' => $rowNumber,
                'email' => $data['Email'] ?? 'N/A',
                'status' => 'failed',
                'message' => implode(', ', $validator->errors()->all())
            ];
        }

        // Cek apakah email sudah ada
        $existingPegawai = MasterPegawai::withTrashed()->where('email', $data['Email'])->first();
        
        DB::beginTransaction();
        
        try {
            if ($existingPegawai) {
                // Update existing pegawai
                $result = $this->updateExistingPegawai($existingPegawai, $data, $rowNumber);
            } else {
                // Create new pegawai + user
                $result = $this->createNewPegawai($data, $rowNumber);
            }
            
            DB::commit();
            return $result;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Validasi data pegawai
     */
    private function validatePegawaiData(array $data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, [
            'Nama Lengkap' => 'required|string|max:255',
            'Email' => 'required|email|max:255',
            'NIP Lama' => 'required|string|max:255',
            'Jenis Kelamin' => 'required|in:L,P',
        ], [
            'Nama Lengkap.required' => 'Nama lengkap harus diisi',
            'Email.required' => 'Email harus diisi',
            'Email.email' => 'Format email tidak valid',
            'NIP Lama.required' => 'NIP Lama harus diisi',
            'Jenis Kelamin.required' => 'Jenis kelamin harus diisi',
            'Jenis Kelamin.in' => 'Jenis kelamin harus L atau P',
        ]);
    }

    /**
     * Create pegawai dan user baru
     */
    private function createNewPegawai(array $data, int $rowNumber): array
    {
        // Cek apakah email sudah digunakan di tabel users
        if (User::withTrashed()->where('email', $data['Email'])->exists()) {
            return [
                'row' => $rowNumber,
                'email' => $data['Email'],
                'status' => 'failed',
                'message' => 'Email sudah digunakan oleh user lain'
            ];
        }

        // Create pegawai
        $pegawai = MasterPegawai::create([
            'nama_lengkap' => $data['Nama Lengkap'],
            'jenis_kelamin' => $data['Jenis Kelamin'],
            'gelar' => $data['Gelar'] ?? null,
            'alias' => $data['Alias'] ?? null,
            'nip_lama' => $data['NIP Lama'],
            'nip_baru' => $data['NIP Baru'] ?? null,
            'nik' => $data['NIK'] ?? null,
            'email' => $data['Email'],
            'nomor_hp' => $data['Nomor HP'] ?? null,
            'pangkat' => $data['Pangkat'] ?? null,
            'jabatan' => $data['Jabatan'] ?? null,
            'pendidikan_tertinggi' => $data['Pendidikan Tertinggi'] ?? null,
            'program_studi' => $data['Program Studi'] ?? null,
            'universitas' => $data['Universitas'] ?? null,
        ]);

        // Auto create user
        $user = User::create([
            'name' => $pegawai->nama_lengkap,
            'email' => $pegawai->email,
            'password' => Hash::make($pegawai->nip_lama),
            'pegawai_id' => $pegawai->id,
            'is_active' => true,
        ]);

        // Assign role default
        $user->assignRole(Role::ANGGOTA_TIM);

        return [
            'row' => $rowNumber,
            'email' => $data['Email'],
            'status' => 'success',
            'message' => 'Pegawai dan user berhasil dibuat'
        ];
    }

    /**
     * Update pegawai yang sudah ada
     */
    private function updateExistingPegawai(MasterPegawai $pegawai, array $data, int $rowNumber): array
    {
        // Update data pegawai
        $pegawai->update([
            'nama_lengkap' => $data['Nama Lengkap'],
            'jenis_kelamin' => $data['Jenis Kelamin'],
            'gelar' => $data['Gelar'] ?? null,
            'alias' => $data['Alias'] ?? null,
            'nip_lama' => $data['NIP Lama'],
            'nip_baru' => $data['NIP Baru'] ?? null,
            'nik' => $data['NIK'] ?? null,
            'email' => $data['Email'],
            'nomor_hp' => $data['Nomor HP'] ?? null,
            'pangkat' => $data['Pangkat'] ?? null,
            'jabatan' => $data['Jabatan'] ?? null,
            'pendidikan_tertinggi' => $data['Pendidikan Tertinggi'] ?? null,
            'program_studi' => $data['Program Studi'] ?? null,
            'universitas' => $data['Universitas'] ?? null,
        ]);

        // Restore jika soft deleted
        if ($pegawai->trashed()) {
            $pegawai->restore();
        }

        // Update user terkait jika ada
        if ($pegawai->user) {
            $pegawai->user->update([
                'name' => $pegawai->nama_lengkap,
                'email' => $pegawai->email,
                'is_active' => true,
            ]);
            
            // Restore user jika soft deleted
            if ($pegawai->user->trashed()) {
                $pegawai->user->restore();
            }
        } else {
            // Buat user baru jika belum ada
            $user = User::create([
                'name' => $pegawai->nama_lengkap,
                'email' => $pegawai->email,
                'password' => Hash::make($pegawai->nip_lama),
                'pegawai_id' => $pegawai->id,
                'is_active' => true,
            ]);
            
            $user->assignRole(Role::ANGGOTA_TIM);
        }

        return [
            'row' => $rowNumber,
            'email' => $data['Email'],
            'status' => 'updated',
            'message' => 'Data pegawai berhasil diperbarui'
        ];
    }

    /**
     * Create pegawai manual (dari form)
     */
    public function createPegawai(array $data): MasterPegawai
    {
        // Validasi email unique
        if (MasterPegawai::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email sudah digunakan oleh pegawai lain'
            ]);
        }

        if (User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email sudah digunakan oleh user lain'
            ]);
        }

        DB::beginTransaction();
        
        try {
            // Create pegawai
            $pegawai = MasterPegawai::create($data);

            // Auto create user
            $user = User::create([
                'name' => $pegawai->nama_lengkap,
                'email' => $pegawai->email,
                'password' => Hash::make($pegawai->nip_lama),
                'pegawai_id' => $pegawai->id,
                'is_active' => true,
            ]);

            // Assign role default
            $user->assignRole(Role::ANGGOTA_TIM);

            DB::commit();
            return $pegawai;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update pegawai dan sync ke user
     */
    public function updatePegawai(MasterPegawai $pegawai, array $data): MasterPegawai
    {
        // Cek email unique jika email berubah
        if ($pegawai->email !== $data['email']) {
            if (MasterPegawai::where('email', $data['email'])->where('id', '!=', $pegawai->id)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'Email sudah digunakan oleh pegawai lain'
                ]);
            }

            if (User::where('email', $data['email'])->where('pegawai_id', '!=', $pegawai->id)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'Email sudah digunakan oleh user lain'
                ]);
            }
        }

        DB::beginTransaction();
        
        try {
            // Update pegawai
            $pegawai->update($data);

            // Sync data ke user jika ada
            if ($pegawai->user) {
                $pegawai->user->update([
                    'name' => $pegawai->nama_lengkap,
                    'email' => $pegawai->email,
                ]);
            }

            DB::commit();
            return $pegawai;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Generate error report untuk download
     */
    public function generateErrorReport(array $errors): array
    {
        $report = [];
        
        foreach ($errors as $error) {
            $report[] = [
                'Baris' => $error['row'],
                'Email' => $error['email'],
                'Status' => $error['status'],
                'Pesan Error' => $error['message'],
            ];
        }
        
        return $report;
    }
}