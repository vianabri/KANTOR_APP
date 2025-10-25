<?php

namespace App\Http\Controllers;

use App\Models\KlpkMember;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class KlpkMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view all kredit lalai')) {
            $members = KlpkMember::where('officer_in_charge', auth()->user()->name)->get();
        } else {
            $members = KlpkMember::all();
        }
        foreach ($members as $m) {
            $lastPay = $m->payments()->orderBy('payment_date', 'desc')->first();
            $m->last_payment_date = $lastPay->payment_date ?? null;

            if ($m->last_payment_date) {
                $m->days_aging = now()->diffInDays($m->last_payment_date);
            } else {
                $m->days_aging = null; // belum pernah bayar
            }
        }

        return view('klpk.index', compact('members'));
    }
    public function followUpList()
    {
        $members = KlpkMember::with('payments')->get();

        $members = $members->filter(function ($m) {
            $lastPay = $m->payments()->orderBy('payment_date', 'desc')->first();
            if (!$lastPay) return true; // belum pernah bayar
            return now()->diffInDays($lastPay->payment_date) > 30;
        });

        return view('klpk.followup', compact('members'));
    }
    public function quickView($id)
    {
        $member = KlpkMember::with([
            'payments' => function ($q) {
                $q->latest()->first();
            },
            'followups' => function ($q) {
                $q->latest()->first();
            }
        ])->findOrFail($id);

        $lastPayment = $member->payments->first();
        $lastFollow = $member->followups->first();

        return response()->json([
            'member' => $member,
            'lastPayment' => $lastPayment,
            'lastFollow' => $lastFollow
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manage kredit lalai');

        return view('klpk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('manage kredit lalai');

        $request->validate([
            'cif_number' => 'required',
            'full_name' => 'required',
            'exit_date' => 'required|date',
            'principal_start' => 'required|numeric',
        ]);

        $data = $request->all();
        $data['principal_remaining'] = $request->principal_start;
        $data['officer_in_charge'] = auth()->user()->name;

        $member = KlpkMember::create($data);

        activity('klpk_member')
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->log('Menambahkan data KLPK baru');

        return redirect()->route('klpk.index')
            ->with('success', 'Data KLPK berhasil ditambahkan.');
    }


    public function report()
    {
        $this->authorize('view all kredit lalai');

        $members = KlpkMember::with('payments')->get();

        foreach ($members as $m) {
            $totalPaid = $m->payments->sum('payment_amount');
            $m->total_paid = $totalPaid;
            $m->progress = $m->principal_start > 0
                ? round(($totalPaid / $m->principal_start) * 100, 2)
                : 0;
        }

        return view('klpk.report-index', compact('members'));
    }

    public function reportPdf()
    {
        $this->authorize('view all kredit lalai');

        $members = KlpkMember::with('payments')->get();

        foreach ($members as $m) {
            $totalPaid = $m->payments->sum('payment_amount');
            $m->total_paid = $totalPaid;
            $m->progress = $m->principal_start > 0
                ? round(($totalPaid / $m->principal_start) * 100, 2)
                : 0;
        }

        activity('klpk_index_pdf')
            ->causedBy(auth()->user())
            ->log('Download laporan Index KLPK');

        $pdf = Pdf::loadView('klpk.report-index-pdf', compact('members'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('Laporan Index KLPK.pdf');
    }
}
