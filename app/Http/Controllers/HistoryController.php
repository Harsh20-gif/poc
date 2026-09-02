<?php

namespace App\Http\Controllers;

use App\Models\LeadInteraction;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LeadInteraction::with(['user', 'lead']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('remark', 'like', "%{$search}%")
                  ->orWhereHas('lead', function ($leadQuery) use ($search) {
                      $leadQuery->where('contact_person', 'like', "%{$search}%")
                                ->orWhere('company_name', 'like', "%{$search}%");
                  });
            });
        }

        $activities = $query->latest()->paginate(20)->withQueryString();

        return view('history.index', compact('activities'));
    }
}
