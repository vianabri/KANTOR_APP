<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenagihanLapangan extends Model
{
    use HasFactory;

    protected $table = 'penagihan_lapangan';

    protected $fillable = [
        'user_id',
        'cif',
        'nama_anggota',
        'wilayah',
        'tanggal_kunjungan',
        'nominal_ditagih',
        'nominal_dibayar',
        'status',
        'tanggal_janji',
        'kendala',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'tanggal_janji'     => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function followups()
    {
        return $this->hasMany(PenagihanFollowup::class, 'penagihan_id');
    }
}
