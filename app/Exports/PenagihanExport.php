<?php

namespace App\Exports;

use App\Models\PenagihanLapangan;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Auth;

class PenagihanExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return ['Tanggal', 'CIF', 'Nama', 'Wilayah', 'Ditagih', 'Dibayar', 'Status', 'Janji', 'Kendala', 'Petugas'];
    }

    public function collection()
    {
        $bulan  = (int)($this->request->bulan ?? now()->month);
        $tahun  = (int)($this->request->tahun ?? now()->year);
        $stafId = (int)($this->request->staf_id ?? 0);
        $wilayah = $this->request->wilayah;

        $user = Auth::user();
        $canViewAll = $user->hasRole('admin') || $user->can('view all penagihan');

        $q = PenagihanLapangan::with('user')
            ->whereYear('tanggal_kunjungan', $tahun)
            ->whereMonth('tanggal_kunjungan', $bulan);

        if (!$canViewAll) $stafId = $user->id;
        if ($stafId > 0) $q->where('user_id', $stafId);
        if ($wilayah) $q->where('wilayah', 'LIKE', "%$wilayah%");
        if ($this->request->filter === 'followup') {
            $q->where('status', 'JANJI')->whereDate('tanggal_janji', '<=', now());
        }

        return $q->orderBy('tanggal_kunjungan', 'asc')->get();
    }

    public function map($r): array
    {
        return [
            optional($r->tanggal_kunjungan)->format('d/m/Y'),
            $r->cif,
            $r->nama_anggota,
            $r->wilayah,
            $r->nominal_ditagih,
            $r->nominal_dibayar,
            $r->status,
            optional($r->tanggal_janji)?->format('d/m/Y'),
            $r->kendala,
            optional($r->user)->name,
        ];
    }
}
