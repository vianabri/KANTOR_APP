<?php

namespace App\Exports;

use App\Models\KreditLalaiHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KreditLalaiExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Request $request) {}

    public function collection(): Collection
    {
        $bulan   = (int)($this->request->bulan ?? now()->month);
        $tahun   = (int)($this->request->tahun ?? now()->year);
        $wilayah = $this->request->wilayah;

        return KreditLalaiHarian::when(
            $wilayah,
            fn($q) => $q->where('wilayah', $wilayah),
            fn($q) => $q->whereNull('wilayah')
        )
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Wilayah', 'Total Piutang', 'Total Lalai', 'CDR (%)', 'Keterangan', 'Input Oleh'];
    }

    public function map($r): array
    {
        return [
            optional($r->tanggal)->format('Y-m-d'),
            $r->wilayah ?? 'GLOBAL',
            (float)$r->total_piutang,
            (float)$r->total_lalai,
            (float)$r->rasio_lalai,
            $r->keterangan,
            optional($r->user)->name,
        ];
    }
}
