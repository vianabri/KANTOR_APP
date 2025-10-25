<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenagihanFollowup extends Model
{
    protected $table = 'penagihan_followup';

    protected $fillable = [
        'penagihan_id',
        'user_id',
        'hasil',
        'nominal_dibayar',
        'tanggal_janji',
        'kendala',
        'catatan',
        'follow_up_date'
    ];

    public function penagihan()
    {
        return $this->belongsTo(PenagihanLapangan::class, 'penagihan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
