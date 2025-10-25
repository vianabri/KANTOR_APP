<?php

namespace App\Http\Controllers;

use App\Models\KlpkMember;
use Illuminate\Http\Request;

class KlpkDashboardController extends Controller
{
    public function index()
    {
        // Total outstanding
        $outstanding = KlpkMember::sum('principal_remaining');

        // Total paid
        $paid = KlpkMember::sum('principal_start') - $outstanding;

        // Recovery rate
        $recoveryRate = $paid > 0
            ? round(($paid / ($paid + $outstanding)) * 100, 2)
            : 0;

        // Status distribution
        $statusData = KlpkMember::selectRaw('status_penagihan, COUNT(*) as total')
            ->groupBy('status_penagihan')
            ->pluck('total', 'status_penagihan');

        // Risk level distribution
        $riskData = KlpkMember::selectRaw('risk_level, COUNT(*) as total')
            ->groupBy('risk_level')
            ->pluck('total', 'risk_level');
        $reminders = KlpkMember::with('followups')
            ->get()
            ->filter(function ($m) {
                $next = $m->followups->sortByDesc('followup_date')->first()->next_followup ?? null;
                if (!$next) return false;
                return now()->greaterThanOrEqualTo($next);
            });

        $reminderCount = $reminders->count();

        return view('klpk.dashboard', compact(
            'outstanding',
            'paid',
            'recoveryRate',
            'statusData',
            'riskData',
            'reminders',
            'reminderCount'
        ));
    }
}
