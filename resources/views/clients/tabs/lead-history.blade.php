<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold">Original Lead Details</h6>
    </div>
    <div class="card-body">
        @if($client->lead)
            <div class="row g-4">
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Source</small>
                    <div class="fw-medium text-dark">{{ $client->lead->source ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Lead Created By</small>
                    <div class="fw-medium text-dark">{{ $client->lead->creator->name ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Assigned To</small>
                    <div class="fw-medium text-dark">{{ $client->lead->assignee->name ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Original Requirement Services</small>
                    <div class="text-dark">
                        @if(is_array($client->lead->services) && count($client->lead->services) > 0)
                            {{ implode(', ', $client->lead->services) }}
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Lead Created Date</small>
                    <div class="text-dark">{{ $client->lead->created_at->format('M d, Y h:i A') }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Converted Date</small>
                    <div class="text-dark">{{ $client->conversion_date ? $client->conversion_date->format('M d, Y') : '—' }}</div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('leads.show', $client->lead) }}" class="btn btn-sm btn-outline-primary">View Full Lead Profile</a>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-info-circle text-muted" style="font-size: 2rem;"></i>
                <p class="mt-3 text-muted">This client was added directly — no originating lead.</p>
            </div>
        @endif
    </div>
</div>
