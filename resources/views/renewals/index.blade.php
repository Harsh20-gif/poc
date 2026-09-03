@extends('layouts.app')
@section('title', 'Renewals Tracking')
@section('subtitle', 'Certifications expiring soon or already expired')

@section('content')
<div class="card mb-4 border-danger border-top border-3">
    <div class="card-header bg-light">
        <h6 class="mb-0 text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Action Required</h6>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Client</th>
                    <th>Certification</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certifications as $cert)
                <tr class="{{ $cert->status === 'expired' ? 'table-danger' : 'table-warning' }}">
                    <td>
                        <div class="fw-bold">{{ $cert->client->company_name ?? 'N/A' }}</div>
                        <div class="text-muted small">{{ $cert->client->client_name }}</div>
                    </td>
                    <td>
                        <div class="fw-medium">{{ $cert->certificate_type }}</div>
                        <div class="small text-muted">{{ $cert->certificate_name }}</div>
                    </td>
                    <td>
                        <div class="fw-bold {{ $cert->expiry_date->isPast() ? 'text-danger' : 'text-dark' }}">
                            {{ $cert->expiry_date->format('M d, Y') }}
                        </div>
                        <small class="text-muted">
                            {{ $cert->expiry_date->diffForHumans() }}
                        </small>
                    </td>
                    <td>
                        @if($cert->status === 'expired')
                            <span class="badge bg-danger text-white">Expired</span>
                        @elseif($cert->status === 'expiring_soon')
                            <span class="badge text-danger border border-danger border-opacity-50" style="background-color: var(--brand-danger-light);">Expiring Soon</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $cert->status)) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('clients.show', $cert->client) }}" class="btn btn-sm btn-outline-dark">View Client</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No upcoming renewals found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center">
    {{ $certifications->links('pagination::bootstrap-5') }}
</div>
@endsection
