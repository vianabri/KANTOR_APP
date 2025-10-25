<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atk;
use App\Models\AtkMasuk;
use App\Models\AtkKeluar;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AtkRekapExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AtkRekapController extends Controller
{
    public function index(Request $request)
    {
        // Filter Periode
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $items = Atk::with(['masuk', 'keluar'])
            ->orderBy('nama_barang')
            ->get();

        // Summary
        $totalValue = $items->sum(function ($i) {
            $hargaAkhir = $i->masuk->last()?->harga_satuan ?? 0;
            return $i->stok * $hargaAkhir;
        });

        $monthlyUsage = AtkKeluar::whereYear('tanggal_keluar', $tahun)
            ->whereMonth('tanggal_keluar', $bulan)
            ->sum('jumlah_keluar');

        // Tren
        $lastMonth = Carbon::create($tahun, $bulan, 1)->subMonth();
        $lastUsage = AtkKeluar::whereYear('tanggal_keluar', $lastMonth->year)
            ->whereMonth('tanggal_keluar', $lastMonth->month)
            ->sum('jumlah_keluar');

        $trend = $monthlyUsage > $lastUsage ? 'NAIK' : ($monthlyUsage < $lastUsage ? 'TURUN' : 'STABIL');

        // Idle Items (≥90 hari tidak bergerak)
        $idleItems = $items->filter(function ($item) {
            $last = collect([
                optional($item->masuk->last())->tanggal_masuk,
                optional($item->keluar->last())->tanggal_keluar
            ])->filter()->max();

            return !$last || Carbon::parse($last)->diffInDays(now()) >= 90;
        });

        $summary = [
            'total_items'    => $items->count(),
            'total_value'    => $totalValue,
            'critical_items' => $items->where('stok', '<', 5)->count(),
            'monthly_usage'  => $monthlyUsage,
            'trend'          => $trend,
            'bulan'          => $bulan,
            'tahun'          => $tahun,
        ];

        return view('atk.rekap', compact('items', 'summary', 'idleItems'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new AtkRekapExport($request), 'rekap_atk.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $items = Atk::with(['masuk', 'keluar'])
            ->orderBy('nama_barang')
            ->get();

        $pdf = Pdf::loadView('atk.export_pdf', [
            'items' => $items,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->setPaper('A4', 'landscape');

        return $pdf->download('rekap_atk.pdf');
    }
}
