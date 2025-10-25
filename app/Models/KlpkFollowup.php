<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlpkFollowup extends Model
{
    protected $fillable = [
        'klpk_id',
        'followup_type',
        'followup_date',
        'notes',
        'officer',
        'followup_status',
        'next_followup'
    ];

    public function member()
    {
        return $this->belongsTo(KlpkMember::class, 'klpk_id');
    }
}
