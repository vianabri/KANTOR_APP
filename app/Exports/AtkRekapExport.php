<?php

namespace App\Exports;

use App\Models\Atk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PDF;

class AtkRekapExport implements FromView
{
    protected $req;
    public function __construct($req)
    {
        $this->req = $req;
    }

    public function view(): View
    {
        $from = $this->req->from;
        $to   = $this->req->to;

        $items = Atk::with(['masuk', 'keluar'])
            ->orderBy('nama_barang')
            ->get();

        return view('atk.export_excel', compact('items'));
    }
    public function exportPDF(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        $items = Atk::with(['masuk', 'keluar'])
            ->orderBy('nama_barang')
            ->get();

        $pdf = PDF::loadView('atk.export_pdf', compact('items'));
        return $pdf->stream('rekap_atk.pdf');
    }
}
