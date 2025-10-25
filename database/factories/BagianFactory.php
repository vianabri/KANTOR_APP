<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BagianFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_bagian' => $this->faker->unique()->randomElement([
                'Keuangan',
                'Pemasaran',
                'Teknologi Informasi',
                'SDM',
                'Operasional'
            ]),
            'is_active' => true,
            'created_by' => 1, // bisa disesuaikan dengan user id
        ];
    }
}
