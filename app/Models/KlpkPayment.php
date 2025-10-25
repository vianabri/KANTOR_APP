<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlpkPayment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'klpk_id',
        'payment_date',
        'payment_amount',
        'payment_method',
        'officer_in_charge',
        'notes',
    ];

    public function member()
    {
        return $this->belongsTo(KlpkMember::class, 'klpk_id');
    }
}
