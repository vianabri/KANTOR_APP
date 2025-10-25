<?php

namespace App\Http\Controllers;

use App\Models\KlpkPayment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KlpkReportController extends Controller
{
    public function monthly(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $payments = KlpkPayment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->with('member')
            ->get();

        $total = $payments->sum('payment_amount');

        return view('klpk.rekap-bulanan', compact('payments', 'total', 'month', 'year'));
    }
    public function monthlyPdf(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $payments = KlpkPayment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->with('member')
            ->get();

        $total = $payments->sum('payment_amount');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'klpk.rekap-bulanan-pdf',
            compact('payments', 'total', 'month', 'year')
        )->setPaper('A4', 'portrait');

        return $pdf->download('Rekap Bulanan KLPK - ' . $month . '-' . $year . '.pdf');
    }
}
