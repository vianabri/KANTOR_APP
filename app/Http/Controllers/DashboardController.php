<?php

namespace App\Http\Controllers;

use App\Models\Atk;
use App\Models\User;
use App\Models\AtkMasuk;
use App\Models\AtkKeluar;
use Illuminate\Support\Carbon;
use App\Models\KreditLalaiHarian;
use Spatie\Permission\Models\Role;


class DashboardController extends Controller
{
    public function index()
    {
        // === USER STATS ===
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $pendingReports = 0;

        $userGrowth = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = array_fill(0, 12, 0);
        foreach ($userGrowth as $item) {
            $data[$item->month - 1] = (int) $item->total;
        }

        // === ATK STATS ===
        $totalAtkItems          = Atk::count();            // jumlah jenis barang
        $totalStok              = (int) Atk::sum('stok');  // total item stok
        $totalNilaiPersediaan   = (int) AtkMasuk::sum('total_harga'); // akumulasi nilai pembelian
        $stokRendah             = Atk::where('stok', '<', 5)->count(); // barang kritis

        // === ATK CHART (per bulan) ===
        // gunakan tanggal transaksi, bukan created_at, agar akurat
        $atkMasuk = AtkMasuk::selectRaw('MONTH(tanggal_masuk) as month, SUM(jumlah_masuk) as total')
            ->groupBy('month')->orderBy('month')->get();
        $atkKeluar = AtkKeluar::selectRaw('MONTH(tanggal_keluar) as month, SUM(jumlah_keluar) as total')
            ->groupBy('month')->orderBy('month')->get();

        $atkMasukData  = array_fill(0, 12, 0);
        $atkKeluarData = array_fill(0, 12, 0);

        foreach ($atkMasuk as $row) {
            $atkMasukData[$row->month - 1] = (int) $row->total;
        }
        foreach ($atkKeluar as $row) {
            $atkKeluarData[$row->month - 1] = (int) $row->total;
        }
        // ===== Snapshot CDR Hari Ini =====
        $today = KreditLalaiHarian::whereDate('tanggal', today())
            ->orderBy('tanggal', 'desc')
            ->first();
        $cdrToday = $today->rasio_lalai ?? null;

        // ===== Snapshot CDR Akhir Bulan Lalu =====
        $lastDayPreviousMonth = Carbon::now()->subMonth()->endOfMonth();
        $prev = KreditLalaiHarian::whereDate('tanggal', $lastDayPreviousMonth)
            ->orderBy('tanggal', 'desc')
            ->first();
        $cdrLastMonth = $prev->rasio_lalai ?? null;

        // ===== Trend (Hijau = turun = Bagus) =====
        if (!is_null($cdrToday) && !is_null($cdrLastMonth)) {
            if ($cdrToday < $cdrLastMonth) {
                $trendIcon = '<span class="text-success fw-bold">⬇ Menurun (Bagus)</span>';
            } elseif ($cdrToday > $cdrLastMonth) {
                $trendIcon = '<span class="text-danger fw-bold">⬆ Naik</span>';
            } else {
                $trendIcon = '<span class="text-secondary fw-bold">▫ Stabil</span>';
            }
        } else {
            $trendIcon = '<span class="text-muted fst-italic">-</span>';
        }

        // ===== (Opsional) Total Users Dashboard =====
        $totalUsers = User::count();


        return view('dashboard', compact(
            'totalUsers',
            'totalRoles',
            'pendingReports',
            'labels',
            'data',
            'totalAtkItems',
            'totalStok',
            'totalNilaiPersediaan',
            'stokRendah',
            'atkMasukData',
            'atkKeluarData',
            'cdrToday',
            'cdrLastMonth',
            'trendIcon',
            'totalUsers' //
        ));
    }
}
