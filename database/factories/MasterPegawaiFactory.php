<?php

namespace Database\Factories;

use App\Models\MasterPegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterPegawai>
 */
class MasterPegawaiFactory extends Factory
{
    protected $model = MasterPegawai::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisKelamin = fake()->randomElement(['L', 'P']);
        $gelar = fake()->randomElement([', S.Kom.', ', S.ST.', ', S.Si.', ', S.Sos.', ', S.E.']);
        
        return [
            'nama_lengkap' => fake()->name(),
            'jenis_kelamin' => $jenisKelamin,
            'gelar' => $gelar,
            'alias' => fake()->firstName(),
            'nip_lama' => fake()->unique()->numerify('#########'),
            'nip_baru' => fake()->numerify('####################'),
            'nik' => fake()->numerify('################'),
            'email' => fake()->unique()->safeEmail(),
            'nomor_hp' => fake()->phoneNumber(),
            'pangkat' => fake()->randomElement(['2A', '2B', '2C', '2D', '3A', '3B', '3C', '3D', '4A', '4B']),
            'jabatan' => fake()->randomElement([
                'Statistisi Ahli Pertama',
                'Statistisi Ahli Muda',
                'Pranata Komputer Ahli Pertama',
                'Pranata Komputer Ahli Muda',
                'Analis Data',
                'Supervisor Survei'
            ]),
            'pendidikan_tertinggi' => fake()->randomElement(['D3', 'D4/S1', 'S2', 'S3']),
            'program_studi' => fake()->randomElement([
                'Statistika',
                'Teknik Informatika',
                'Sistem Informasi',
                'Ekonomi',
                'Matematika',
                'Geografi'
            ]),
            'universitas' => fake()->randomElement([
                'Universitas Gadjah Mada',
                'Institut Teknologi Bandung',
                'Universitas Indonesia',
                'Institut Pertanian Bogor',
                'Universitas Diponegoro',
                'Universitas Brawijaya'
            ]),
        ];
    }

    /**
     * State untuk kepala BPS
     */
    public function kepalaBps(): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan' => 'Kepala BPS Kota Magelang',
            'pangkat' => '4B',
            'gelar' => ', S.ST., M.A.',
            'pendidikan_tertinggi' => 'S2',
        ]);
    }

    /**
     * State untuk supervisor
     */
    public function supervisor(): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan' => 'Supervisor Survei',
            'pangkat' => fake()->randomElement(['3C', '3D', '4A']),
            'pendidikan_tertinggi' => fake()->randomElement(['S1', 'S2']),
        ]);
    }
}