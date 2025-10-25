<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jabatan',
        'bagian_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    // === RELASI ===
    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    public function pegawais()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function riwayatJabatans()
    {
        return $this->hasMany(RiwayatJabatan::class);
    }

    // === RELASI KE USER ===
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // === SCOPE ===
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}
