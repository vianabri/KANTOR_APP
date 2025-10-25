<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KreditLalaiHarian extends Model
{
    protected $table = 'kredit_lalai_harian';

    protected $fillable = [
        'tanggal',
        'wilayah',
        'total_piutang',
        'total_lalai',
        'rasio_lalai',
        'user_id',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_piutang' => 'decimal:2',
        'total_lalai'   => 'decimal:2',
        'rasio_lalai'   => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
