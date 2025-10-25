<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pegawai;
use App\Models\Jabatan;

class RiwayatJabatanFactory extends Factory
{
    public function definition(): array
    {
        $mulai = $this->faker->dateTimeBetween('-5 years', '-1 year');
        $selesai = $this->faker->boolean(60) ? $this->faker->dateTimeBetween($mulai, 'now') : null;

        return [
            'pegawai_id' => Pegawai::inRandomOrder()->first()?->id ?? Pegawai::factory(),
            'jabatan_id' => Jabatan::inRandomOrder()->first()?->id ?? Jabatan::factory(),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'is_current' => $selesai ? false : true,
            'keterangan' => $this->faker->sentence(),
            'created_by' => 1,
        ];
    }
}
