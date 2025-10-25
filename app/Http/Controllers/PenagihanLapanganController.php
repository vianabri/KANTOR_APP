<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenagihanLapangan;
use App\Models\PenagihanFollowup;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PenagihanExport;
use Maatwebsite\Excel\Facades\Excel;


class PenagihanLapanganController extends Controller
{
    public function create()
    {
        return view('penagihan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_kunjungan' => 'required|date',
            'cif' => 'required|string|max:20',
            'nama_anggota' => 'required|string|max:100',
            'wilayah' => 'required|string|max:50',
            'nominal_ditagih' => 'required|integer|min:1000',
            'nominal_dibayar' => 'nullable|integer|min:0',
            'status' => 'required|in:BAYAR,JANJI,GAGAL',
            'tanggal_janji' => 'nullable|date',
            'kendala' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        if ($validated['status'] === 'BAYAR') {
            $validated['nominal_dibayar'] = $validated['nominal_dibayar'] ?? 0;
        } elseif ($validated['status'] === 'JANJI') {
            $validated['tanggal_janji'] = $validated['tanggal_janji'] ?? now()->addDays(7);
        } else {
            $validated['kendala'] = $validated['kendala'] ?? 'Tidak berhasil ditagih';
        }

        PenagihanLapangan::create($validated);

        return redirect()->route('penagihan.laporan')
            ->with('success', 'Kunjungan penagihan berhasil dicatat!');
    }


    public function laporan(Request $request)
    {
        // Ambil parameter filter
        $bulan  = (int)($request->bulan ?? now()->month);
        $tahun  = (int)($request->tahun ?? now()->year);
        $stafId = (int)($request->staf_id ?? 0);
        $wilayah = $request->wilayah;

        $user = Auth::user();
        $canViewAll = $user->can('view all penagihan') || $user->hasRole('admin');

        // Query data penagihan dasar
        $query = PenagihanLapangan::with(['user'])
            ->whereYear('tanggal_kunjungan', $tahun)
            ->whereMonth('tanggal_kunjungan', $bulan);

        // Filter staf, jika bukan admin → otomatis staf = dirinya sendiri
        if (!$canViewAll) {
            $stafId = $user->id;
        }
        if ($stafId > 0) {
            $query->where('user_id', $stafId);
        }

        // Filter wilayah
        if ($wilayah) {
            $query->where('wilayah', 'LIKE', "%$wilayah%");
        }

        // Filter follow-up pending (dari tombol reminder navbar)
        if ($request->filter === 'followup') {
            $query->where('status', 'JANJI')
                ->whereDate('tanggal_janji', '<=', now());
        }

        $items = $query->orderBy('tanggal_kunjungan', 'asc')->get();

        // === SUMMARY ===
        $totalVisit = $items->count();
        $totalTagih = $items->sum('nominal_ditagih');
        $totalBayar = $items->sum('nominal_dibayar');

        $successRate = $totalTagih > 0
            ? round(($totalBayar / $totalTagih) * 100, 2)
            : 0;

        $janjiCount = $items->where('status', 'JANJI')->count();

        // Tren dibanding bulan sebelumnya
        $lastMonth = Carbon::create($tahun, $bulan, 1)->subMonth();
        $lastPay = PenagihanLapangan::whereYear('tanggal_kunjungan', $lastMonth->year)
            ->whereMonth('tanggal_kunjungan', $lastMonth->month)
            ->sum('nominal_dibayar');

        $trend = $totalBayar > $lastPay ? 'NAIK' : ($totalBayar < $lastPay ? 'TURUN' : 'STABIL');

        // KPI Follow-Up
        $followUpPending = PenagihanLapangan::where('status', 'JANJI')
            ->whereDate('tanggal_janji', '<=', now())
            ->when(!$canViewAll, fn($q) => $q->where('user_id', Auth::id()))
            ->count();

        $followUpBerhasil = PenagihanFollowup::where('hasil', 'BAYAR')
            ->when(!$canViewAll, fn($q) => $q->where('user_id', Auth::id()))
            ->count();

        $followUpTotal = PenagihanFollowup::when(!$canViewAll, fn($q) => $q->where('user_id', Auth::id()))->count();

        $successRateFollowUp = $followUpTotal > 0
            ? round(($followUpBerhasil / $followUpTotal) * 100, 2)
            : 0;

        // Ranking Kendala
        $kendalaRank = $items->whereNotNull('kendala')
            ->groupBy('kendala')
            ->map(fn($g) => $g->count())
            ->sortDesc()
            ->take(5);

        return view('penagihan.laporan', [
            'items' => $items,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'stafId' => $stafId,
            'wilayah' => $wilayah,
            'totalVisit' => $totalVisit,
            'totalTagih' => $totalTagih,
            'totalBayar' => $totalBayar,
            'successRate' => $successRate,
            'janjiCount' => $janjiCount,
            'trend' => $trend,
            'followUpPending' => $followUpPending,
            'followUpBerhasil' => $followUpBerhasil,
            'successRateFollowUp' => $successRateFollowUp,
            'kendalaRank' => $kendalaRank,
            'canViewAll' => $canViewAll,
            'stafList' => User::all(),
        ]);
    }

    // Simpan Follow-Up (dari modal)
    public function followupStore(Request $request, $id)
    {
        $request->validate([
            'hasil' => 'required|in:BAYAR,JANJI,GAGAL',
            'nominal_dibayar' => 'nullable|integer|min:0',
            'kendala' => 'nullable|string|max:255',
            'tanggal_janji' => 'nullable|date',
            'catatan' => 'nullable|string|max:255',
        ]);

        $penagihan = PenagihanLapangan::findOrFail($id);

        // Simpan histori follow-up
        PenagihanFollowup::create([
            'penagihan_id' => $penagihan->id,
            'user_id' => Auth::id(),
            'hasil' => $request->hasil,
            'nominal_dibayar' => $request->hasil === 'BAYAR' ? $request->nominal_dibayar : null,
            'tanggal_janji' => $request->hasil === 'JANJI' ? $request->tanggal_janji : null,
            'kendala' => $request->hasil === 'GAGAL' ? $request->kendala : null,
            'catatan' => $request->catatan,
        ]);

        // === Update status utama & progress pembayaran ===
        if ($request->hasil === 'BAYAR') {

            // Tambah pembayaran (untuk cicilan)
            $penagihan->nominal_dibayar = ($penagihan->nominal_dibayar ?? 0) + $request->nominal_dibayar;

            // Jika sudah lunas
            if ($penagihan->nominal_dibayar >= $penagihan->nominal_ditagih) {
                $penagihan->status = 'BAYAR';
                $penagihan->tanggal_janji = null;
                $penagihan->kendala = null;
            } else {
                // Kalau belum lunas tetap janji bayar
                $penagihan->status = 'JANJI';
            }
        }

        if ($request->hasil === 'JANJI') {
            $penagihan->status = 'JANJI';
            $penagihan->tanggal_janji = $request->tanggal_janji;
        }

        if ($request->hasil === 'GAGAL') {
            $penagihan->status = 'GAGAL';
            $penagihan->kendala = $request->kendala;
        }

        $penagihan->save();

        return back()->with('success', 'Follow-Up berhasil disimpan!');
    }
    // ✅ Export ke Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(new PenagihanExport($request), 'laporan_penagihan.xlsx');
    }

    // ✅ Export ke PDF
    public function exportPdf(Request $request)
    {
        $bulan  = (int)($request->bulan ?? now()->month);
        $tahun  = (int)($request->tahun ?? now()->year);
        $stafId = (int)($request->staf_id ?? 0);
        $wilayah = $request->wilayah;

        $user = Auth::user();
        $canViewAll = $user->can('view all penagihan') || $user->hasRole('admin');

        $query = PenagihanLapangan::with('user')
            ->whereYear('tanggal_kunjungan', $tahun)
            ->whereMonth('tanggal_kunjungan', $bulan);

        if (!$canViewAll) {
            $stafId = Auth::id();
        }

        if ($stafId > 0) {
            $query->where('user_id', $stafId);
        }

        if ($wilayah) {
            $query->where('wilayah', 'LIKE', "%$wilayah%");
        }

        if ($request->filter === 'followup') {
            $query->where('status', 'JANJI')
                ->whereDate('tanggal_janji', '<=', now());
        }

        $items = $query->orderBy('tanggal_kunjungan')->get();

        $pdf = Pdf::loadView('penagihan.export_pdf', [
            'items'  => $items,
            'bulan'  => $bulan,
            'tahun'  => $tahun,
            'stafId' => $stafId,
            'wilayah' => $wilayah
        ])->setPaper('A4', 'landscape');

        return $pdf->download("Laporan_Penagihan_{$bulan}_{$tahun}.pdf");
    }
}
