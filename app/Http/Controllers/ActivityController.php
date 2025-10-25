<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:view logs']);
    }

    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->orderByDesc('created_at');

        if ($search = $request->input('q')) {
            $query->where('description', 'like', "%{$search}%")
                ->orWhereHas('causer', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($userId = $request->input('user')) {
            $query->where('causer_id', $userId);
        }

        $activities = $query->paginate(15)->withQueryString();

        return view('activity.index', compact('activities'));
    }
}
