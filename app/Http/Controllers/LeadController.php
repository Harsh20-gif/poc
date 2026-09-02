<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_leads' => Lead::where('is_active', true)->count(),
            'pipeline_value' => Client::where('verification_status', '!=', 'completed')->sum('deal_amount'),
            'total_clients' => Client::count(),
            'active_certificates' => \App\Models\Certification::where('status', 'active')->count(),
            'completed_projects' => Client::where('verification_status', 'completed')->count(),
            'staff_members' => \App\Models\User::count(),
        ];

        $query = Lead::with(['assignee', 'interactions' => function($q) {
            $q->latest();
        }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'converted');
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if (!$request->has('show_deactivated')) {
            $query->where('is_active', true);
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        $statuses = Lead::STATUSES;
        $sources = Lead::whereNotNull('source')->distinct()->pluck('source');
        $salesUsers = \App\Models\User::whereIn('role', ['admin', 'sales'])->get();

        return view('leads.index', compact('leads', 'statuses', 'sources', 'stats', 'salesUsers'));
    }

    public function create()
    {
        $staff = \App\Models\User::whereIn('role', ['admin', 'sales'])->get();
        $sources = Lead::whereNotNull('source')->distinct()->pluck('source');
        
        $allServices = Lead::whereNotNull('services')->pluck('services')->flatten()->unique()->filter()->values()->all();
        if (empty($allServices)) {
            $allServices = ['ISO 9001', 'ISO 14001', 'ISO 45001', 'ISO 27001', 'CE Marking', 'BIS Certification', 'FSSAI', 'GMP', 'Hallmark', 'GST Registration'];
        }

        return view('leads.create', compact('staff', 'sources', 'allServices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_person' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:20',
            'alternate_mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:255',
            'source' => 'required|string|max:255',
            'services' => 'nullable|array',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['created_by'] = Auth::id();

        $services = $validated['services'] ?? [];
        if ($request->filled('custom_services')) {
            $custom = array_filter(array_map('trim', explode(',', $request->custom_services)));
            $services = array_unique(array_merge($services, $custom));
        }
        $validated['services'] = array_values($services);

        $lead = Lead::create($validated);

        \App\Models\LeadInteraction::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'created',
            'remark' => 'Lead created manually.',
            'details' => ['source' => $lead->source]
        ]);

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    public function showImportForm()
    {
        return view('leads.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        $header = fgetcsv($handle);
        $imported = 0;
        $skipped = 0;
        $warnings = [];
        $rowNum = 1;

        $validSources = ['Website', 'LinkedIn', 'Instagram', 'Cold Call', 'Direct Visit', 'Other'];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;
                
                // Format: contact_person,company_name,mobile,alternate_mobile,email,city,source,services
                if (count($row) < 3) {
                    $skipped++;
                    $warnings[] = "Row $rowNum skipped: Invalid format.";
                    continue;
                }

                $contact_person = trim($row[0]);
                $company_name = trim($row[1]) ?: null;
                $mobile = trim($row[2]);
                $alternate_mobile = trim($row[3] ?? '') ?: null;
                $email = trim($row[4] ?? '') ?: null;
                $city = trim($row[5] ?? '') ?: null;
                $source = trim($row[6] ?? 'Other');
                $servicesStr = trim($row[7] ?? '');

                if (empty($contact_person) || empty($mobile)) {
                    $skipped++;
                    $warnings[] = "Row $rowNum skipped: Missing required fields (contact_person or mobile).";
                    continue;
                }

                if (!in_array($source, $validSources)) {
                    $source = 'Other';
                }

                $services = empty($servicesStr) ? [] : array_map('trim', explode('|', $servicesStr));

                Lead::create([
                    'contact_person' => $contact_person,
                    'company_name' => $company_name,
                    'mobile' => $mobile,
                    'alternate_mobile' => $alternate_mobile,
                    'email' => $email,
                    'city' => $city,
                    'source' => $source,
                    'services' => $services,
                    'created_by' => Auth::id(),
                    'status' => 'pending',
                    'is_active' => true,
                ]);

                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        } finally {
            fclose($handle);
        }

        return redirect()->route('leads.index')->with('success', "Import complete. $imported imported, $skipped skipped.");
    }

    public function show(Request $request, Lead $lead)
    {
        $interactionsQuery = $lead->interactions()->with('user');
        
        if ($request->filled('timeline_type')) {
            $interactionsQuery->where('type', $request->timeline_type);
        }
        
        if ($request->filled('timeline_search')) {
            $interactionsQuery->where('remark', 'like', '%' . $request->timeline_search . '%');
        }
        
        $interactions = $interactionsQuery->latest()->get();

        $lead->load(['client', 'creator', 'assignee']);
        $staff = \App\Models\User::whereIn('role', ['admin', 'sales'])->get();
        return view('leads.show', compact('lead', 'staff', 'interactions'));
    }

    public function edit(Lead $lead)
    {
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'alternate_mobile' => 'nullable|string|max:20',
            'services' => 'nullable|array',
            'email' => 'nullable|email',
            'city' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.show', $lead)->with('success', 'Lead updated.');
    }

    public function assign(Request $request, Lead $lead)
    {
        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $lead->update(['assigned_to' => $request->assigned_to]);

        $assignedUser = $request->assigned_to ? \App\Models\User::find($request->assigned_to) : null;
        \App\Models\LeadInteraction::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'assignment',
            'remark' => $assignedUser ? 'Assigned to ' . $assignedUser->name : 'Unassigned',
            'details' => ['assigned_to' => $request->assigned_to]
        ]);

        return redirect()->back()->with('success', 'Lead assign kar di gayi.');
    }

    public function deactivate(Request $request, Lead $lead)
    {
        $request->validate([
            'deactivation_reason' => 'required|in:High Price,Competitor Chosen,Not Interested,Unresponsive,Other',
        ]);

        $lead->update([
            'is_active' => false,
            'status' => 'deactivated',
            'deactivation_reason' => $request->deactivation_reason,
        ]);

        \App\Models\LeadInteraction::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'status_change',
            'remark' => 'Lead deactivated: ' . $request->deactivation_reason,
            'details' => ['status' => 'deactivated', 'reason' => $request->deactivation_reason]
        ]);

        return redirect()->route('leads.show', $lead)->with('success', 'Lead deactivated.');
    }

    public function reactivate(Lead $lead)
    {
        $lead->update([
            'is_active' => true,
            'status' => 'pending', // Revert to pending or previous state? Pending is safe.
            'deactivation_reason' => null,
        ]);

        \App\Models\LeadInteraction::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'status_change',
            'remark' => 'Lead reactivated.',
            'details' => ['status' => 'pending']
        ]);

        return redirect()->route('leads.show', $lead)->with('success', 'Lead reactivated.');
    }

    public function convert(Request $request, Lead $lead)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'deal_amount' => 'required|numeric|min:0',
            'conversion_date' => 'required|date',
            'finalized_services' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $client = Client::create([
                'lead_id' => $lead->id,
                'client_name' => $request->client_name,
                'company_name' => $request->company_name,
                'deal_amount' => $request->deal_amount,
                'conversion_date' => $request->conversion_date,
                'finalized_services' => $request->finalized_services ?? [],
            ]);

            $lead->update(['status' => 'converted']);
            
            \App\Models\LeadInteraction::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'type' => 'status_change',
                'remark' => 'Lead converted to Client.',
                'details' => ['status' => 'converted', 'client_id' => $client->id]
            ]);
            
            DB::commit();

            return redirect()->route('clients.show', $client)->with('success', 'Lead successfully converted to Client!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error converting lead: ' . $e->getMessage());
        }
    }
}
