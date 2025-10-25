<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset Permission Cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 📌 Permissions lengkap seluruh modul
        $permissions = [

            // Users & Roles
            'manage users',
            'manage roles',
            'manage permissions',

            // Logs
            'view logs',

            // Inventaris ATK
            'manage atk',

            // Penagihan Lapangan
            'manage penagihan',
            'view all penagihan',

            // Kredit Lalai Harian
            'manage kredit lalai',
            'view all kredit lalai',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web'
            ]);
        }

        // 📌 Roles
        $admin       = Role::firstOrCreate(['name' => 'admin']);
        $supervisor  = Role::firstOrCreate(['name' => 'supervisor']);
        $staff       = Role::firstOrCreate(['name' => 'staff']);
        $viewer      = Role::firstOrCreate(['name' => 'viewer']);

        // ✅ Assign Permissions

        // Admin = semua akses
        $admin->syncPermissions(Permission::all());

        // Supervisor = melihat semua data
        $supervisor->syncPermissions([
            'manage penagihan',
            'view all penagihan',
            'manage kredit lalai',
            'view all kredit lalai',
        ]);

        $staff->syncPermissions([
            'manage penagihan',
            'manage kredit lalai',
        ]);

        // Viewer = hanya lihat Dashboard
        $viewer->syncPermissions([]);

        // Opsional: User pertama jadi admin
        if ($first = \App\Models\User::first()) {
            $first->assignRole('admin');
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
