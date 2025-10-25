<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bagian;
use App\Models\Jabatan;

class StrukturSeeder extends Seeder
{
    public function run(): void
    {
        $bagians = [
            'Usaha',
            'Organisasi',
            'Pemberdayaan'
        ];

        foreach ($bagians as $nama) {
            Bagian::create(['nama_bagian' => $nama]);
        }

        $jabatanData = [
            ['nama_jabatan' => 'Manajer', 'bagian_id' => 1],
            ['nama_jabatan' => 'Kepala Bagian', 'bagian_id' => 2],
            ['nama_jabatan' => 'Staf Pemberdayaan', 'bagian_id' => 3],
            ['nama_jabatan' => 'Staf Administrasi Kredit', 'bagian_id' => 1],
            ['nama_jabatan' => 'Staf Penagihan dan Survei', 'bagian_id' => 1],
            ['nama_jabatan' => 'Staf Kasir', 'bagian_id' => 1],
        ];

        foreach ($jabatanData as $jab) {
            Jabatan::create($jab);
        }
    }
}
