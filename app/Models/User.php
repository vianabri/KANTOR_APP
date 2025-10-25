<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'position',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 🔹 Logging konfigurasi untuk activitylog
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->useLogName('user');
    }

    // 🔥 Super Admin override (versi aman)
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        // Admin selalu punya izin penuh
        if ($this->hasRole('admin')) {
            return true;
        }

        // Cek izin langsung dari trait HasRoles
        return $this->hasDirectPermission($permission) ||
            $this->getPermissionsViaRoles()->contains('name', $permission);
    }
}
