<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bagian;

class JabatanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_jabatan' => $this->faker->jobTitle(),
            'bagian_id' => Bagian::inRandomOrder()->first()?->id ?? Bagian::factory(),
            'is_active' => true,
            'created_by' => 1,
        ];
    }
}
