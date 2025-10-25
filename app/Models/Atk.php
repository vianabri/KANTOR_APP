<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atk extends Model
{
    protected $table = 'atk';

    protected $fillable = [
        'nama_barang',
        'satuan',
        'stok',
        'keterangan',
    ];

    public function masuk()
    {
        return $this->hasMany(AtkMasuk::class, 'atk_id');
    }

    public function keluar()
    {
        return $this->hasMany(AtkKeluar::class, 'atk_id');
    }
}
