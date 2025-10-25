<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bagian;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\RiwayatJabatan;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🧩 STEP 1: Buat Roles & Permissions (pakai Spatie)
        $this->seedRolesAndPermissions();

        // 🧩 STEP 2: Buat Admin User
        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // 🧩 STEP 3: Buat beberapa Bagian
        $bagians = Bagian::factory(5)->create();

        // 🧩 STEP 4: Tiap bagian punya 3 Jabatan
        $bagians->each(function ($bagian) {
            Jabatan::factory(3)->create(['bagian_id' => $bagian->id]);
        });

        // 🧩 STEP 5: Buat 20 Pegawai dengan Jabatan random
        $jabatans = Jabatan::all();
        $pegawais = Pegawai::factory(20)->create()->each(function ($pegawai) use ($jabatans) {
            $jabatan = $jabatans->random();
            $pegawai->update(['jabatan_id' => $jabatan->id]);

            // Buat riwayat jabatan otomatis
            RiwayatJabatan::create([
                'pegawai_id' => $pegawai->id,
                'jabatan_id' => $jabatan->id,
                'tanggal_mulai' => now()->subMonths(rand(6, 36)),
                'is_current' => true,
                'created_by' => 1,
            ]);
        });

        // 🧩 STEP 6: Tambahkan user HRD contoh
        $hrd = User::factory()->create([
            'name' => 'HRD Manager',
            'email' => 'hrd@example.com',
            'password' => Hash::make('password'),
        ]);
        $hrd->assignRole('hrd');

        // 🧩 STEP 7: Tambahkan user staf contoh
        $staff = User::factory()->create([
            'name' => 'Staf Umum',
            'email' => 'staf@example.com',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole('staf');

        $this->command->info('✅ Database seeded successfully!');
        $this->command->warn('➡ Login sebagai admin@example.com / password');
    }

    private function seedRolesAndPermissions(): void
    {
        // Reset cache Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // === Permissions dasar sistem ===
        $permissions = [
            'view pegawai',
            'manage pegawai',
            'view bagian',
            'manage bagian',
            'view jabatan',
            'manage jabatan',
            'view logs',
            'manage users',
            'manage roles'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // === Roles ===
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $hrd = Role::firstOrCreate(['name' => 'hrd']);
        $staf = Role::firstOrCreate(['name' => 'staf']);

        // === Assign permissions ===
        $admin->givePermissionTo(Permission::all()); // admin dapat semua
        $hrd->givePermissionTo([
            'view pegawai',
            'manage pegawai',
            'view bagian',
            'view jabatan',
        ]);
        $staf->givePermissionTo(['view pegawai']);
    }
}
