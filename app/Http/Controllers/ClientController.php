<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

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
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('clients.index', compact('clients', 'stats'));
    }

    public function show(Client $client)
    {
        $client->load(['lead', 'documents.verifier', 'certifications']);
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
}
