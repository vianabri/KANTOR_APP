<?php

namespace App\Http\Controllers;

use App\Models\KreditLalaiHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KreditLalaiExport;
use Barryvdh\DomPDF\Facade\Pdf;

class KreditLalaiHarianController extends Controller
{
    public function index(Request $request)
    {
        $bulan   = (int)($request->bulan ?? now()->month);
        $tahun   = (int)($request->tahun ?? now()->year);
        $wilayah = $request->wilayah;

        // Ambil data bulan terpilih
        $items = $this->getFilteredData($bulan, $tahun, $wilayah);

        // Snapshot = ambil data HARI TERAKHIR bulan ini
        $snap = $items->sortByDesc('tanggal')->first();

        $snapPiutang = $snap->total_piutang ?? 0;
        $snapLalai   = $snap->total_lalai ?? 0;
        $snapRasio   = $snap->rasio_lalai ?? 0;

        // Hitung rata-rata bulan ini
        $avgRasio = $items->avg('rasio_lalai') ?? 0;

        // BANDINKAN Bln Lalu
        $prev = Carbon::create($tahun, $bulan, 1)->subMonth();
        $prevItems = $this->getFilteredData($prev->month, $prev->year, $wilayah);
        $prevAvg = $prevItems->avg('rasio_lalai') ?? 0;

        // Logika Trend
        if ($prevItems->count() == 0) {
            $trendText = "Belum ada data bulan lalu";
            $trendColor = "text-secondary";
        } elseif ($avgRasio > $prevAvg) {
            $trendText = "Naik 🚨 (lebih buruk)";
            $trendColor = "text-danger";
        } elseif ($avgRasio < $prevAvg) {
            $trendText = "Turun ✅ (lebih baik)";
            $trendColor = "text-success";
        } else {
            $trendText = "Stabil 😐";
            $trendColor = "text-secondary";
        }

        return view('kredit-lalai.index', compact(
            'items',
            'bulan',
            'tahun',
            'wilayah',
            'snapPiutang',
            'snapLalai',
            'snapRasio',
            'avgRasio',
            'prevAvg',
            'trendText',
            'trendColor'
        ));
    }


    public function create()
    {
        return view('kredit-lalai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'        => 'required|date',
            'total_piutang'  => 'required|numeric|min:0',
            'total_lalai'    => 'required|numeric|min:0|lte:total_piutang',
            'wilayah'        => 'nullable|string|max:100',
            'keterangan'     => 'nullable|string|max:255',
        ]);

        // ✅ Pastikan unique per tanggal & wilayah
        $exists = KreditLalaiHarian::where('tanggal', $request->tanggal)
            ->where('wilayah', $request->wilayah)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'tanggal' => 'Data harian wilayah ini sudah ada.'
            ])->withInput();
        }

        $rasio = $request->total_piutang > 0
            ? round(($request->total_lalai / $request->total_piutang) * 100, 2)
            : 0;

        KreditLalaiHarian::create([
            'tanggal'        => $request->tanggal,
            'wilayah'        => $request->wilayah,
            'total_piutang'  => $request->total_piutang,
            'total_lalai'    => $request->total_lalai,
            'rasio_lalai'    => $rasio,
            'keterangan'     => $request->keterangan,
            'user_id'        => Auth::id(),
        ]);

        return redirect()->route('kredit-lalai.index')->with('success', 'Data harian disimpan.');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new KreditLalaiExport($request), 'kredit_lalai_harian.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $bulan   = (int)($request->bulan ?? now()->month);
        $tahun   = (int)($request->tahun ?? now()->year);
        $wilayah = $request->wilayah;

        $items = $this->getFilteredData($bulan, $tahun, $wilayah);

        $pdf = Pdf::loadView('kredit-lalai.export_pdf', compact(
            'items',
            'bulan',
            'tahun',
            'wilayah'
        ))->setPaper('A4', 'landscape');

        return $pdf->download("kredit_lalai_harian_{$tahun}-{$bulan}.pdf");
    }

    // ✅ Helper Query
    private function getFilteredData(int $bulan, int $tahun, $wilayah = null)
    {
        return KreditLalaiHarian::query()
            ->when($wilayah, fn($q) => $q->where('wilayah', $wilayah))
            ->when(!$wilayah, fn($q) => $q->whereNull('wilayah'))
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->get();
    }
}
