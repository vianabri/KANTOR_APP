<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtkKeluar extends Model
{
    protected $table = 'atk_keluar'; // Pastikan sesuai dengan nama tabel di migration

    protected $fillable = [
        'atk_id',          // barang ATK apa yang keluar
        'jumlah_keluar',   // berapa banyak barang yang keluar
        'penerima',        // siapa yang menerima barang
        'tanggal_keluar',  // kapan barang keluar
        'keperluan',       // untuk apa barang digunakan
    ];

    // Relasi ke master barang ATK
    public function atk()
    {
        return $this->belongsTo(Atk::class, 'atk_id');
    }
}
