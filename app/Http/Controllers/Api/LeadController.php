<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Staff;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    /**
     * List all leads with optional filters.
     */
    public function index(Request $request)
    {
        $leads = Lead::with('assignedStaff')
            ->when($request->has('is_client'), fn($q) => $q->where('is_client', $request->boolean('is_client')))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->service_type, fn($q) => $q->where('service_type', $request->service_type))
            ->when($request->assigned_to, fn($q) => $q->where('assigned_to', $request->assigned_to))
            ->when($request->source, fn($q) => $q->where('source', $request->source))
            ->when($request->search, fn($q) => $q
                ->where('company_name', 'like', '%' . $request->search . '%')
                ->orWhere('contact_person', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%')
            )
            ->latest()
            ->get();

        return response()->json([
            'total' => $leads->count(),
            'leads' => $leads->map(fn($l) => $this->format($l)),
        ]);
    }

    /**
     * Create a new lead.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'   => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email'          => 'nullable|email',
            'phone'          => 'required|string|max:15',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'service_type'   => 'required|in:ISO 9001,ISO 14001,ISO 45001,ISO 27001,CE Marking,BIS Certification,FSSAI,GMP,Other',
            'status'         => 'in:new,contacted,proposal_sent,negotiation,in_progress,completed,lost',
            'source'         => 'in:website,referral,cold_call,email_campaign,walk_in,other',
            'expected_value' => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'assigned_to'    => 'nullable|exists:staff,id',
        ]);

        $lead = Lead::create($validated);
        $lead->load('assignedStaff');

        return response()->json([
            'message' => 'Lead created successfully',
            'lead'    => $this->format($lead),
        ], 201);
    }

    /**
     * Show a single lead.
     */
    public function show(Lead $lead)
    {
        $lead->load('assignedStaff');

        return response()->json([
            'lead' => $this->format($lead, true),
        ]);
    }

    /**
     * Update a lead.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'company_name'   => 'sometimes|string|max:255',
            'contact_person' => 'sometimes|string|max:255',
            'email'          => 'nullable|email',
            'phone'          => 'sometimes|string|max:15',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'service_type'   => 'sometimes|in:ISO 9001,ISO 14001,ISO 45001,ISO 27001,CE Marking,BIS Certification,FSSAI,GMP,Other',
            'status'         => 'sometimes|in:new,contacted,proposal_sent,negotiation,in_progress,completed,lost',
            'source'         => 'sometimes|in:website,referral,cold_call,email_campaign,walk_in,other',
            'expected_value' => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'assigned_to'    => 'nullable|exists:staff,id',
        ]);

        $lead->update($validated);
        $lead->load('assignedStaff');

        return response()->json([
            'message' => 'Lead updated successfully',
            'lead'    => $this->format($lead),
        ]);
    }

    /**
     * Delete a lead.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response()->json(['message' => 'Lead deleted successfully']);
    }

    /**
     * Summary stats for leads dashboard.
     */
    public function stats()
    {
        $total = Lead::count();

        return response()->json([
            'summary' => [
                'total_leads'     => $total,
                'new'             => Lead::where('status', 'new')->count(),
                'contacted'       => Lead::where('status', 'contacted')->count(),
                'proposal_sent'   => Lead::where('status', 'proposal_sent')->count(),
                'in_progress'     => Lead::where('status', 'in_progress')->count(),
                'completed'       => Lead::where('status', 'completed')->count(),
                'lost'            => Lead::where('status', 'lost')->count(),
            ],
            'by_service' => Lead::selectRaw('service_type, count(*) as count')
                ->groupBy('service_type')
                ->orderByDesc('count')
                ->get(),
            'by_source' => Lead::selectRaw('source, count(*) as count')
                ->groupBy('source')
                ->orderByDesc('count')
                ->get(),
            'total_pipeline_value' => Lead::whereNotIn('status', ['completed', 'lost'])
                ->sum('expected_value'),
            'total_won_value' => Lead::where('status', 'completed')
                ->sum('expected_value'),
            'follow_ups_today' => Lead::whereDate('follow_up_date', today())
                ->whereNotIn('status', ['completed', 'lost'])
                ->with('assignedStaff')
                ->get()
                ->map(fn($l) => [
                    'id'           => $l->id,
                    'company_name' => $l->company_name,
                    'contact_person' => $l->contact_person,
                    'phone'        => $l->phone,
                    'service_type' => $l->service_type,
                    'assigned_to'  => $l->assignedStaff?->name,
                ]),
        ]);
    }

    /**
     * Certify a lead and convert to client.
     */
    public function certify(Request $request, Lead $lead)
    {
        if (!$lead->assigned_to) {
            return response()->json([
                'message' => 'Cannot certify a lead without an assigned staff member.'
            ], 422);
        }

        $validated = $request->validate([
            'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number',
            'issue_date'         => 'required|date',
            'expiry_date'        => 'required|date|after_or_equal:issue_date',
            'document_url'       => 'nullable|string|max:255',
        ]);

        $certificate = DB::transaction(function () use ($lead, $validated) {
            // Convert lead to client
            $lead->update([
                'is_client' => true,
                'client_since' => now(),
                'status' => 'completed',
            ]);

            // Create certificate
            return Certificate::create([
                'lead_id'            => $lead->id,
                'certificate_number' => $validated['certificate_number'],
                'certificate_type'   => $lead->service_type,
                'issue_date'         => $validated['issue_date'],
                'expiry_date'        => $validated['expiry_date'],
                'document_url'       => $validated['document_url'] ?? null,
                'status'             => 'active',
            ]);
        });

        return response()->json([
            'message'     => 'Lead certified successfully and converted to Client!',
            'lead'        => $this->format($lead),
            'certificate' => $certificate,
        ], 201);
    }

    private function format(Lead $lead, bool $full = false): array
    {
        $data = [
            'id'             => $lead->id,
            'company_name'   => $lead->company_name,
            'contact_person' => $lead->contact_person,
            'email'          => $lead->email,
            'phone'          => $lead->phone,
            'city'           => $lead->city,
            'state'          => $lead->state,
            'service_type'   => $lead->service_type,
            'status'         => $lead->status,
            'source'         => $lead->source,
            'expected_value' => $lead->expected_value,
            'follow_up_date' => $lead->follow_up_date?->toDateString(),
            'assigned_to'    => $lead->assignedStaff ? [
                'id'          => $lead->assignedStaff->id,
                'name'        => $lead->assignedStaff->name,
                'designation' => $lead->assignedStaff->designation,
            ] : null,
            'is_client'      => (bool) $lead->is_client,
            'client_since'   => $lead->client_since?->toDateTimeString(),
            'created_at'     => $lead->created_at->toDateTimeString(),
        ];

        if ($full) {
            $data['notes'] = $lead->notes;
        }

        return $data;
    }
}
