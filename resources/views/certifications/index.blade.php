@extends('layouts.app')
@section('title', 'Certificates Directory')
@section('subtitle', 'Manage all issued certificates.')

@section('topbar_actions')
    <button class="btn btn-outline-secondary text-dark border-secondary me-2" onclick="window.location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
    </button>
    <button type="button" class="btn text-white border-0 shadow-sm me-2" style="background: linear-gradient(135deg, #0284c7, #38bdf8);" data-bs-toggle="modal" data-bs-target="#newLeadModal">
        <i class="bi bi-plus-lg me-1"></i> New Lead
    </button>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-outline-secondary text-dark border-secondary"><i class="bi bi-person-plus"></i> Add Staff</a>
@endsection

@section('content')

@include('layouts.partials.stat_cards')

<div class="card mb-4 border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
    <div class="card-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">Issued Certificates Directory</h5>
    </div>
    <div class="card-body px-4 pt-3 pb-4">
        
        <div class="table-responsive mt-3">
            <table class="table table-borderless table-hover align-middle mb-0 text-dark">
                <thead style="border-bottom: 1px solid var(--card-border);">
                    <tr>
                        <th class="text-muted small text-uppercase pb-3">Certificate No.</th>
                        <th class="text-muted small text-uppercase pb-3">Company Name</th>
                        <th class="text-muted small text-uppercase pb-3">Certificate Type</th>
                        <th class="text-muted small text-uppercase pb-3">Issue Date</th>
                        <th class="text-muted small text-uppercase pb-3">Expiry Date</th>
                        <th class="text-muted small text-uppercase pb-3">Status</th>
                        <th class="text-muted small text-uppercase pb-3">Assigned Staff</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certifications as $cert)
                    <tr style="border-bottom: 1px solid var(--card-border);">
                        <td class="py-3 fw-medium text-dark">CERT-{{ str_pad($cert->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3 text-dark">{{ $cert->client->company_name ?? '—' }}</td>
                        <td class="py-3 text-dark">{{ $cert->certificate_type ?? '—' }}</td>
                        <td class="py-3 text-dark">
                            @if($cert->issue_date)
                                {{ \Carbon\Carbon::parse($cert->issue_date)->format('M d, Y') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-dark">
                            @if($cert->expiry_date)
                                {{ \Carbon\Carbon::parse($cert->expiry_date)->format('M d, Y') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @php
                                $statusConfig = match($cert->status) {
                                    'active' => ['bg' => 'rgba(16, 185, 129, 0.1)', 'color' => '#10B981'], // Green
                                    'expiring_soon' => ['bg' => 'rgba(245, 158, 11, 0.1)', 'color' => '#F59E0B'], // Orange
                                    'expired' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'color' => '#EF4444'], // Red
                                    'renewal_triggered' => ['bg' => 'rgba(100, 116, 139, 0.1)', 'color' => '#94A3B8'], // Gray
                                    default => ['bg' => 'rgba(100, 116, 139, 0.1)', 'color' => '#94A3B8']
                                };
                            @endphp
                            <span class="badge rounded-pill px-3 fw-medium" style="background-color: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['color'] }};">
                                {{ ucfirst(str_replace('_', ' ', $cert->status)) }}
                            </span>
                        </td>
                        <td class="py-3">
                            @php
                                $assignee = $cert->client?->lead?->assignee;
                            @endphp
                            @if($assignee)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background: linear-gradient(135deg, #0284c7, #38bdf8); width: 24px; height: 24px; font-size: 10px;">
                                        {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                    </div>
                                    <span class="text-dark small">{{ $assignee->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No issued certificates yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $certifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
