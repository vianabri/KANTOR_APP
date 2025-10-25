<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @mixin IdeHelperRole
 */
class Role extends SpatieRole
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'guard_name',
        'description', // tambahan kolom opsional
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('role')
            ->logOnly(['name', 'description'])
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => 'Menambahkan role baru',
                    'updated' => 'Mengubah role',
                    'deleted' => 'Menghapus role',
                    default => ucfirst($eventName) . ' role',
                };
            })
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
