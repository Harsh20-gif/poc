@extends('layouts.app')
@section('title', 'Leads Management')
@section('subtitle', 'Track and convert potential certification clients.')

@section('topbar_actions')
    <button class="btn btn-outline-secondary text-dark border-secondary me-2" onclick="window.location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
    </button>
    <a href="{{ route('leads.create') }}" class="btn text-white border-0 shadow-sm me-2" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
        <i class="bi bi-plus-lg me-1"></i> New Lead
    </a>
    <a href="{{ route('leads.import.form') }}" class="btn btn-outline-secondary text-dark border-secondary"><i class="bi bi-upload"></i> Bulk Import</a>
@endsection

@section('content')

@include('layouts.partials.stat_cards')

<div class="card mb-4 border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
    <div class="card-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">All CRM Certification Leads</h5>
    </div>
    <div class="card-body px-4 pt-3 pb-4">
        <form method="GET" action="{{ route('leads.index') }}" class="row g-3 align-items-center mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control text-dark border-secondary rounded-pill" placeholder="Search Name, Email, Mobile..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select text-dark border-secondary rounded-pill">
                    <option value="" class="text-dark">Active (Not Converted)</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" class="text-dark" {{ request('status') === (string)$key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="source" class="form-select text-dark border-secondary rounded-pill">
                    <option value="" class="text-dark">All Sources</option>
                    @foreach($sources as $src)
                        <option value="{{ $src }}" class="text-dark" {{ request('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input bg-secondary border-secondary" type="checkbox" name="show_deactivated" value="1" id="show_deactivated" {{ request('show_deactivated') ? 'checked' : '' }}>
                    <label class="form-check-label text-muted ms-1" for="show_deactivated">
                        Show Deactivated
                    </label>
                </div>
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-secondary rounded-pill w-100">Filter</button>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0 text-dark">
                <thead style="border-bottom: 1px solid var(--card-border);">
                    <tr>
                        <th class="text-muted small text-uppercase pb-3">Company Name</th>
                        <th class="text-muted small text-uppercase pb-3">Contact Person</th>
                        <th class="text-muted small text-uppercase pb-3">Phone/Email</th>
                        <th class="text-muted small text-uppercase pb-3">City</th>
                        <th class="text-muted small text-uppercase pb-3">Service</th>
                        <th class="text-muted small text-uppercase pb-3">Status</th>
                        <th class="text-muted small text-uppercase pb-3">Value</th>
                        <th class="text-muted small text-uppercase pb-3">Follow Up</th>
                        <th class="text-muted small text-uppercase pb-3">Assignee</th>
                        <th class="text-muted small text-uppercase pb-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                    <tr style="border-bottom: 1px solid var(--card-border);" class="{{ !$lead->is_active ? 'opacity-50' : '' }}">
                        <td class="py-3 fw-medium text-dark">{{ $lead->company_name ?? '—' }}</td>
                        <td class="py-3 text-dark">
                            <div>{{ $lead->contact_person }}</div>
                            <div class="text-muted small">{{ $lead->mobile }}</div>
                        </td>
                        <td class="py-3">
                            <div class="fw-bold text-dark">{{ $lead->mobile }}</div>
                            <div class="text-muted small">{{ $lead->email ?? '—' }}</div>
                        </td>
                        <td class="py-3 text-dark">{{ $lead->city ?? '—' }}</td>
                        <td class="py-3">
                            @if(is_array($lead->services) && count($lead->services) > 0)
                                <span class="badge bg-secondary bg-opacity-10 text-dark fw-normal rounded-pill px-3">{{ $lead->services[0] }}</span>
                                @if(count($lead->services) > 1)
                                    <span class="badge bg-secondary bg-opacity-10 text-muted fw-normal rounded-pill ms-1">+{{ count($lead->services) - 1 }}</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @php
                                $statusConfig = match($lead->status) {
                                    'new', 'pending' => ['bg' => 'rgba(59, 130, 246, 0.1)', 'color' => '#3B82F6'], // Blue
                                    'in_conversation' => ['bg' => 'rgba(139, 92, 246, 0.1)', 'color' => '#8B5CF6'], // Purple
                                    'proposal_sent' => ['bg' => 'rgba(245, 158, 11, 0.1)', 'color' => '#F59E0B'], // Orange
                                    'converted' => ['bg' => 'rgba(16, 185, 129, 0.1)', 'color' => '#10B981'], // Green
                                    'deactivated', 'lost' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'color' => '#EF4444'], // Red
                                    'renewal' => ['bg' => 'rgba(245, 158, 11, 0.1)', 'color' => '#F59E0B'], // Orange
                                    default => ['bg' => 'rgba(100, 116, 139, 0.1)', 'color' => '#94A3B8']
                                };
                            @endphp
                            <span class="badge rounded-pill px-3 fw-medium" style="background-color: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['color'] }};">
                                {{ $statuses[$lead->status] ?? ucfirst(str_replace('_', ' ', $lead->status)) }}
                            </span>
                        </td>
                        <td class="py-3 fw-bold text-dark">—</td>
                        <td class="py-3 text-dark">
                            @php
                                $nextFollowUp = $lead->interactions->first()?->next_follow_up_date;
                            @endphp
                            @if($nextFollowUp)
                                {{ \Carbon\Carbon::parse($nextFollowUp)->format('M d, Y') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($lead->assignee)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background: linear-gradient(135deg, #0284c7, #38bdf8); width: 24px; height: 24px; font-size: 10px;">
                                        {{ strtoupper(substr($lead->assignee->name, 0, 1)) }}
                                    </div>
                                    <span class="text-dark small">{{ $lead->assignee->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-end">
                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-sm btn-outline-secondary text-dark border-secondary me-1">View</a>
                            @if($lead->status === 'converted')
                                <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3 py-2 border border-success border-opacity-25">Converted</span>
                            @else
                                <button type="button" class="btn btn-sm text-white" style="background-color: #10B981;" onclick="openCertifyModal({{ $lead->id }}, '{{ addslashes($lead->company_name ?: $lead->contact_person) }}')">
                                    Certify Lead
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">No leads found matching your criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $leads->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Certify Lead Modal -->
<div class="modal fade" id="certifyModal" tabindex="-1" aria-labelledby="certifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: var(--card-bg); border: 1px solid var(--card-border);">
            <div class="modal-header border-secondary border-opacity-25">
                <h5 class="modal-title fw-bold text-dark" id="certifyModalLabel">Certify Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="certifyForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-4">You are converting <strong id="certifyLeadName" class="text-dark"></strong> into a certified Client. Please provide the final deal details.</p>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark">Client/Entity Name <span class="text-danger">*</span></label>
                        <input type="text" name="client_name" class="form-control text-dark border-secondary" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark">Deal Amount (Value) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary border-secondary text-dark border-opacity-25">$</span>
                            <input type="number" step="0.01" name="deal_amount" class="form-control text-dark border-secondary" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark">Conversion Date <span class="text-danger">*</span></label>
                        <input type="date" name="conversion_date" class="form-control text-dark border-secondary" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="button" class="btn btn-outline-secondary text-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white" style="background-color: #10B981;">Certify Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCertifyModal(leadId, leadName) {
        document.getElementById('certifyLeadName').innerText = leadName;
        // Dynamically set the form action to route('leads.convert', $lead)
        document.getElementById('certifyForm').action = "/leads/" + leadId + "/convert";
        
        var certifyModal = new bootstrap.Modal(document.getElementById('certifyModal'));
        certifyModal.show();
    }
</script>
@endsection
