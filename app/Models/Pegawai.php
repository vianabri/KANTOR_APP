<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'no_hp',
        'alamat',
        'tanggal_masuk',
        'status_kerja',
        'jabatan_id',
        'foto',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'tanggal_masuk',
        'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔗 RELASI MODEL
    |--------------------------------------------------------------------------
    */

    // Pegawai punya satu jabatan saat ini
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    // Riwayat jabatan pegawai
    public function riwayatJabatans()
    {
        return $this->hasMany(RiwayatJabatan::class);
    }

    // Bagian melalui jabatan (relasi tidak langsung)
    public function bagian()
    {
        return $this->hasOneThrough(Bagian::class, Jabatan::class, 'id', 'id', 'jabatan_id', 'bagian_id');
    }

    // User yang membuat
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // User yang mengupdate
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | 🧩 ACCESSOR & HELPER
    |--------------------------------------------------------------------------
    */

    // Ambil URL foto pegawai (atau avatar default)
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        // Avatar default dari nama
        return "https://ui-avatars.com/api/?name=" . urlencode($this->nama) . "&background=0D6EFD&color=fff";
    }

    // Status label (untuk badge di tabel)
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_kerja) {
            'Tetap'   => '<span class="badge bg-success">Tetap</span>',
            'Kontrak' => '<span class="badge bg-warning text-dark">Kontrak</span>',
            'Magang'  => '<span class="badge bg-info text-dark">Magang</span>',
            default   => '<span class="badge bg-secondary">-</span>',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | 🗑️ SOFT DELETE LOGIC
    |--------------------------------------------------------------------------
    */

    // Pastikan relasi ikut terhapus logis (tidak permanen)
    protected static function booted()
    {
        static::deleting(function ($pegawai) {
            if ($pegawai->isForceDeleting()) {
                // Jika benar-benar dihapus permanen
                $pegawai->riwayatJabatans()->forceDelete();
            } else {
                // Jika hanya soft delete
                $pegawai->riwayatJabatans()->delete();
            }
        });

        static::restoring(function ($pegawai) {
            // Saat dipulihkan, restore juga riwayat
            $pegawai->riwayatJabatans()->withTrashed()->restore();
        });
    }
}
