<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MakeAdmin extends Command
{
    protected $signature = 'make:admin {email}';
    protected $description = 'Ubah user dengan email tertentu menjadi admin';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            return $this->error("User dengan email {$email} tidak ditemukan.");
        }

        $role = Role::firstOrCreate(['name' => 'admin']);
        $permissions = Permission::pluck('name')->toArray();
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        $this->info("✅ {$user->name} sekarang sudah menjadi admin penuh.");
    }
}
