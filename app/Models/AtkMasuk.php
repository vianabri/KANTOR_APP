<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtkMasuk extends Model
{
    protected $table = 'atk_masuk';

    protected $fillable = [
        'atk_id',
        'jumlah_masuk',
        'harga_satuan',
        'total_harga',
        'tanggal_masuk',
        'supplier',
        'keterangan',
    ];

    public function atk()
    {
        return $this->belongsTo(Atk::class, 'atk_id');
    }
}
