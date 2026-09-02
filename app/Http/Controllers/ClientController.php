<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_leads' => \App\Models\Lead::where('is_active', true)->count(),
            'pipeline_value' => Client::where('verification_status', '!=', 'completed')->sum('deal_amount'),
            'total_clients' => Client::count(),
            'active_certificates' => \App\Models\Certification::where('status', 'active')->count(),
            'completed_projects' => Client::where('verification_status', 'completed')->count(),
            'staff_members' => \App\Models\User::count(),
        ];

        $query = Client::with('lead.assignee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('owner', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('clients.index', compact('clients', 'stats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'website' => 'nullable|url|max:255',
            'vat_number' => 'nullable|string|max:255',
            'client_group' => 'nullable|string|max:255',
            'currency' => 'required|string|in:INR,USD,EUR,GBP',
            'currency_symbol' => 'required|string|max:5',
            'deal_amount' => 'nullable|numeric|min:0',
            'conversion_date' => 'nullable|date',
            'finalized_services' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->route('clients.index')
                ->withErrors($validator)
                ->withInput()
                ->with('client_modal', true);
        }

        $client = Client::create([
            'lead_id' => null,
            'company_name' => $request->company_name,
            'client_name' => $request->owner,
            'owner' => $request->owner,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'phone' => $request->phone,
            'website' => $request->website,
            'vat_number' => $request->vat_number,
            'client_group' => $request->client_group,
            'currency' => $request->currency,
            'currency_symbol' => $request->currency_symbol,
            'deal_amount' => $request->deal_amount ?? 0,
            'conversion_date' => $request->conversion_date ?? now()->toDateString(),
            'verification_status' => 'pending',
            'finalized_services' => $request->finalized_services ?? [],
        ]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.')->with('client_modal', false);
    }

    public function show(Client $client)
    {
        $client->load(['lead.interactions.user', 'documents.verifier', 'certifications.documents']);
        return view('clients.show', compact('client'));
    }

    public function updateStatus(Request $request, Client $client)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:pending,scheduled,completed',
            'survey_date' => 'nullable|date',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client)->with('success', 'Verification status updated.');
    }

    public function addInteraction(Request $request, Client $client)
    {
        $request->validate([
            'remark' => 'required|string',
            'next_follow_up_date' => 'nullable|date',
        ]);

        $client->lead->interactions()->create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'remark' => $request->remark,
            'next_follow_up_date' => $request->next_follow_up_date,
        ]);

        return redirect()->route('clients.show', $client)->with('success', 'Interaction logged successfully.');
    }
}
