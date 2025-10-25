<?php

namespace App\Http\Controllers;

use App\Models\KlpkMember;
use App\Models\KlpkPayment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KlpkPaymentController extends Controller
{
    public function create($klpk_id)
    {
        $member = KlpkMember::findOrFail($klpk_id);
        return view('klpk.payment-create', compact('member'));
    }

    public function store(Request $request, $klpk_id)
    {
        $member = KlpkMember::findOrFail($klpk_id);

        $request->validate([
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:1000'
        ]);

        KlpkPayment::create([
            'klpk_id' => $klpk_id,
            'payment_date' => $request->payment_date,
            'payment_amount' => $request->payment_amount,
            'payment_method' => $request->payment_method,
            'officer_in_charge' => $request->officer_in_charge,
            'notes' => $request->notes,
        ]);

        // Kurangi sisa pokok
        $member->principal_remaining -= $request->payment_amount;
        $member->save();

        return redirect()->route('klpk.index')->with('success', 'Pembayaran berhasil dicatat.');
    }
    public function history($klpk_id)
    {
        $member = KlpkMember::with('payments')->findOrFail($klpk_id);

        return view('klpk.payment-history', compact('member'));
    }
    public function historyPdf($klpk_id)
    {
        $member = KlpkMember::with('payments')->findOrFail($klpk_id);

        $pdf = Pdf::loadView('klpk.payment-history-pdf', compact('member'))
            ->setPaper('A4', 'portrait');

        $fileName = 'Histori Pembayaran - ' . $member->full_name . '.pdf';

        return $pdf->download($fileName);
        activity('klpk_payment_pdf')
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->log('Download PDF histori pembayaran');
    }
}
