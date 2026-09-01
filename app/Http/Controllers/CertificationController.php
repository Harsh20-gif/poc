<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Client;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_leads' => \App\Models\Lead::where('is_active', true)->count(),
            'pipeline_value' => Client::where('verification_status', '!=', 'completed')->sum('deal_amount'),
            'total_clients' => Client::count(),
            'active_certificates' => Certification::where('status', 'active')->count(),
            'completed_projects' => Client::where('verification_status', 'completed')->count(),
            'staff_members' => \App\Models\User::count(),
        ];

        $query = Certification::with('client.lead.assignee');
        
        $certifications = $query->orderBy('issue_date', 'desc')->paginate(15)->withQueryString();

        return view('certifications.index', compact('certifications', 'stats'));
    }

    public function store(Request $request, Client $client)
    {
        $request->validate([
            'certificate_name' => 'required|string',
            'certificate_type' => 'required|string',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'certificate_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $path = $file->storeAs("client-certificates/{$client->id}", $file->getClientOriginalName(), 'public');
        }

        Certification::create([
            'client_id' => $client->id,
            'certificate_name' => $request->certificate_name,
            'certificate_type' => $request->certificate_type,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'certificate_pdf_path' => $path,
            'status' => 'active',
        ]);

        return redirect()->route('clients.show', $client)->with('success', 'Certificate issued.');
    }

    public function renewals()
    {
        $certifications = Certification::with('client')
            ->whereIn('status', ['expiring_soon', 'expired'])
            ->orderBy('expiry_date', 'asc')
            ->paginate(15);
            
        return view('renewals.index', compact('certifications'));
    }
}
