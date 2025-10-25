<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_bagian',
        'is_active',
        'created_by',
        'updated_by',
    ];

    // === RELASI ===
    public function jabatans()
    {
        return $this->hasMany(Jabatan::class);
    }

    // === RELASI KE USER (opsional, jika user table aktif) ===
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
