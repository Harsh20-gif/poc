<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold">Client Information</h6>
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil me-1"></i> Edit</a>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <small class="text-muted d-block mb-1">Company Name</small>
                <div class="fw-medium text-dark">{{ $client->company_name ?? '—' }}</div>
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block mb-1">Contact Person (Owner)</small>
                <div class="fw-medium text-dark">{{ $client->owner ?? $client->client_name ?? '—' }}</div>
            </div>
            <div class="col-md-12">
                <small class="text-muted d-block mb-1">Address</small>
                <div class="text-dark">{{ $client->address ?? '—' }}</div>
            </div>
            <div class="col-md-3 col-sm-6">
                <small class="text-muted d-block mb-1">City</small>
                <div class="text-dark">{{ $client->city ?? '—' }}</div>
            </div>
            <div class="col-md-3 col-sm-6">
                <small class="text-muted d-block mb-1">State</small>
                <div class="text-dark">{{ $client->state ?? '—' }}</div>
            </div>
            <div class="col-md-3 col-sm-6">
                <small class="text-muted d-block mb-1">Zip Code</small>
                <div class="text-dark">{{ $client->zip ?? '—' }}</div>
            </div>
            <div class="col-md-3 col-sm-6">
                <small class="text-muted d-block mb-1">Country</small>
                <div class="text-dark">{{ $client->country ?? '—' }}</div>
            </div>
            <div class="col-md-4 col-sm-6">
                <small class="text-muted d-block mb-1">Phone</small>
                <div class="text-dark">{{ $client->phone ?? '—' }}</div>
            </div>
            <div class="col-md-4 col-sm-6">
                <small class="text-muted d-block mb-1">Website</small>
                <div class="text-dark">
                    @if($client->website)
                        <a href="{{ $client->website }}" target="_blank" class="text-primary text-decoration-none">{{ $client->website }}</a>
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <small class="text-muted d-block mb-1">VAT Number</small>
                <div class="text-dark">{{ $client->vat_number ?? '—' }}</div>
            </div>
            <div class="col-md-4 col-sm-6">
                <small class="text-muted d-block mb-1">Client Group</small>
                <div class="text-dark">
                    @if($client->client_group)
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">{{ $client->client_group }}</span>
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <small class="text-muted d-block mb-1">Currency</small>
                <div class="text-dark">{{ $client->currency ?? '—' }} ({{ $client->currency_symbol ?? '' }})</div>
            </div>
        </div>
    </div>
</div>
