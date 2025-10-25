<?php

namespace App\Http\Controllers;

use App\Models\KlpkMember;
use App\Models\KlpkFollowup;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class KlpkFollowupController extends Controller
{
    public function create($klpk_id)
    {
        $this->authorize('manage kredit lalai');

        $member = KlpkMember::findOrFail($klpk_id);
        return view('klpk.followup-create', compact('member'));
    }

    public function store(Request $request, $klpk_id)
    {
        $this->authorize('manage kredit lalai');

        $request->validate([
            'followup_type' => 'required|string',
            'followup_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'followup_status' => 'nullable|string',
            'next_followup' => 'nullable|date|after_or_equal:followup_date',
        ]);

        $followup = KlpkFollowup::create([
            'klpk_id' => $klpk_id,
            'followup_type' => $request->followup_type,
            'followup_date' => $request->followup_date,
            'notes' => $request->notes,
            'followup_status' => $request->followup_status,
            'next_followup' => $request->next_followup,
            'officer' => auth()->user()->name,
        ]);

        activity('klpk_followup')
            ->causedBy(auth()->user())
            ->performedOn($followup)
            ->withProperties([
                'klpk_id' => $klpk_id,
                'status'  => $request->followup_status,
            ])
            ->log('Input follow-up KLPK');

        return redirect()->route('klpk.payment.history', $klpk_id)
            ->with('success', 'Tindak lanjut berhasil disimpan.');
    }
}
