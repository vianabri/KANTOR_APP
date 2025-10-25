<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KlpkMember extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'klpk_members';
    protected $primaryKey = 'klpk_id';

    protected $fillable = [
        'cif_number',
        'full_name',
        'id_number',
        'phone_number',
        'address',
        'exit_date',
        'loan_reference',
        'principal_start',
        'principal_remaining',
        'officer_in_charge',
        'risk_level',
        'collateral_info',
        'status_penagihan',
        'first_notes',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Data KLPK {$this->full_name} telah {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('klpk_member')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function payments()
    {
        return $this->hasMany(KlpkPayment::class, 'klpk_id');
    }
    public function followups()
    {
        return $this->hasMany(KlpkFollowup::class, 'klpk_id');
    }
}
