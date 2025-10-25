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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Permission Modules
        // ✅ Permission Modules + Action Granular
        $permissions = [
            // User & Role Management
            'manage users',
            'manage roles',
            'manage permissions',

            // Audit
            'view logs',

            // KLPK MODULE ACCESS
            'klpk.view',
            'klpk.dashboard.view',

            // KLPK ACTIONS
            'klpk.create',
            'klpk.edit',
            'klpk.delete',

            // KLPK PAYMENTS ACTIONS
            'klpk.payment.view',
            'klpk.payment.create',

            // KLPK FOLLOWUP ACTIONS
            'klpk.followup.view',
            'klpk.followup.create',

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

        // ✅ Roles
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $staff      = Role::firstOrCreate(['name' => 'staff']);
        $viewer     = Role::firstOrCreate(['name' => 'viewer']);

        // ✅ Role Permissions
        $admin->syncPermissions(Permission::all());

        $supervisor->syncPermissions([
            'klpk.view',
            'klpk.dashboard.view',
            'klpk.create',
            'klpk.edit',
            'klpk.delete',
            'klpk.payment.view',
            'klpk.payment.create',
            'klpk.followup.view',
            'klpk.followup.create',

            'manage penagihan',
            'view all penagihan',
            'manage kredit lalai',
            'view all kredit lalai',
            'view logs',
        ]);

        $staff->syncPermissions([
            'view klpk',
            'manage klpk',
            'manage klpk followup',
            'manage klpk payments',

            'manage penagihan',
            'manage kredit lalai',
        ]);

        $viewer->syncPermissions([
            'klpk.view',
            'klpk.payment.view',
            'klpk.payment.create',
            'klpk.followup.view',
            'klpk.followup.create',
            'klpk.edit', // hanya untuk miliknya (dibatasi query)
            'manage penagihan',
            'manage kredit lalai',
        ]);

        $viewer->syncPermissions([
            'klpk.view',
            'klpk.dashboard.view',
        ]);

        // ✅ User pertama otomatis Admin
        if ($first = \App\Models\User::first()) {
            $first->assignRole('admin');
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
