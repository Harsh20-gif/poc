@extends('layouts.app')
@section('title', 'Lead Details')
@section('subtitle', $lead->company_name ?: $lead->contact_person)

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <!-- Lead Info Card -->
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Contact Info</h6>
                <span class="badge bg-{{ $lead->status === 'pending' ? 'warning text-dark' : ($lead->status === 'converted' ? 'success' : 'secondary') }}">{{ ucfirst(str_replace('_', ' ', $lead->status)) }}</span>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <small class="text-muted d-block">Contact Person</small>
                        <span class="fw-medium">{{ $lead->contact_person }}</span>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">Company</small>
                        <span>{{ $lead->company_name ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">Mobile</small>
                        <span>{{ $lead->mobile }}</span>
                        @if($lead->alternate_mobile) <br><span class="text-muted small">Alt: {{ $lead->alternate_mobile }}</span> @endif
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">Email</small>
                        <span>{{ $lead->email ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">City</small>
                        <span>{{ $lead->city ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">Source</small>
                        <span class="badge bg-light text-dark border">{{ $lead->source }}</span>
                    </li>
                    <li>
                        <small class="text-muted d-block mb-1">Interested Services</small>
                        @if($lead->services)
                            @foreach($lead->services as $service)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle me-1 mb-1">{{ $service }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">None specified</span>
                        @endif
                    </li>
                </ul>

                @if($lead->is_active && $lead->status !== 'converted')
                <div class="mt-4 pt-3 border-top d-grid gap-2">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#convertModal">Convert to Client</button>
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deactivateModal">Deactivate</button>
                </div>
                @elseif(!$lead->is_active)
                <div class="mt-4 pt-3 border-top">
                    <div class="alert alert-danger p-2 mb-3 small">
                        <strong>Deactivated:</strong> {{ $lead->deactivation_reason }}
                    </div>
                    <form action="{{ route('leads.reactivate', $lead) }}" method="POST" class="d-grid">
                        @csrf
                        <button class="btn btn-outline-secondary" data-confirm="Reactivate this lead?">Reactivate Lead</button>
                    </form>
                </div>
                @elseif($lead->status === 'converted')
                <div class="mt-4 pt-3 border-top d-grid gap-2">
                    <a href="{{ route('clients.show', $lead->client) }}" class="btn btn-primary">View Client Profile</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Interaction Timeline -->
        @include('partials.interaction-timeline', [
            'interactions' => $lead->interactions,
            'showForm' => $lead->is_active && $lead->status !== 'converted',
            'submitUrl' => route('interactions.store', $lead)
        ])
    </div>
</div>

<!-- Convert Modal -->
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Convert to Client</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('leads.convert', $lead) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Client Name *</label>
                        <input type="text" name="client_name" class="form-control" value="{{ $lead->contact_person }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ $lead->company_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deal Amount (₹) *</label>
                        <input type="number" name="deal_amount" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Conversion Date *</label>
                        <input type="date" name="conversion_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <label class="form-label">Finalized Services</label>
                    <div class="row g-2 mb-3">
                        @foreach(['ISO 9001', 'ISO 14001', 'ISO 45001', 'ISO 27001', 'CE Marking', 'BIS Certification', 'FSSAI', 'GMP', 'Hallmark', 'GST Registration'] as $service)
                            <div class="col-6">
                                <label class="d-flex align-items-center">
                                    <input type="checkbox" name="finalized_services[]" value="{{ $service }}" class="form-check-input me-2" {{ (is_array($lead->services) && in_array($service, $lead->services)) ? 'checked' : '' }}>
                                    <small>{{ $service }}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Complete Conversion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Deactivate Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Deactivate Lead</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('leads.deactivate', $lead) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Reason for Deactivation *</label>
                        <select name="deactivation_reason" class="form-select" required>
                            <option value="">Select Reason</option>
                            <option value="High Price">High Price</option>
                            <option value="Competitor Chosen">Competitor Chosen</option>
                            <option value="Not Interested">Not Interested</option>
                            <option value="Unresponsive">Unresponsive</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Deactivation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
