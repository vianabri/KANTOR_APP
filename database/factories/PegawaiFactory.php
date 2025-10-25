<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Jabatan;

class PegawaiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nip' => $this->faker->unique()->numerify('EMP###'),
            'nama' => $this->faker->name(),
            'jabatan_id' => Jabatan::inRandomOrder()->first()?->id ?? Jabatan::factory(),
            'tanggal_masuk' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'status_kerja' => $this->faker->randomElement(['Tetap', 'Kontrak', 'Magang']),
            'email' => $this->faker->unique()->safeEmail(),
            'no_hp' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'is_active' => true,
            'created_by' => 1,
        ];
    }
}
