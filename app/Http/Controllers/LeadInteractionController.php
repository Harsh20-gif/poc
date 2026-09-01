<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadInteractionController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $request->validate([
            'remark' => 'required|string',
            'next_follow_up_date' => 'nullable|date',
        ]);

        LeadInteraction::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'remark' => $request->remark,
            'next_follow_up_date' => $request->next_follow_up_date,
        ]);

        if ($lead->status === 'pending') {
            $lead->update(['status' => 'in_conversation']);
        }

        return redirect()->route('leads.show', $lead)->with('success', 'Interaction logged.');
    }
}
