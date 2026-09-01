@extends('layouts.app')
@section('title', 'Dashboard Overview')
@section('subtitle', 'Welcome back! Here is what is happening today.')

@section('topbar_actions')
    <button class="btn btn-outline-secondary text-dark border-secondary me-2" onclick="window.location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
    </button>
    <a href="{{ route('leads.create') }}" class="btn text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
        <i class="bi bi-plus-lg me-1"></i> New Lead
    </a>
@endsection

@section('content')

@include('layouts.partials.stat_cards')

<!-- Main Content Columns -->
<div class="row g-4">
    <!-- Left Column: High Value Deals -->
    <div class="col-lg-8">
        <div class="card h-100 border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
            <div class="card-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Recent High-Value Leads</h5>
                <a href="{{ route('clients.index') }}" class="btn btn-sm btn-outline-secondary text-dark border-secondary">View All</a>
            </div>
            <div class="card-body p-0 mt-3">
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle mb-0 text-dark">
                        <thead style="border-bottom: 1px solid var(--card-border);">
                            <tr>
                                <th class="text-muted small text-uppercase px-4 pb-3">Company</th>
                                <th class="text-muted small text-uppercase pb-3">Contact</th>
                                <th class="text-muted small text-uppercase pb-3">Service</th>
                                <th class="text-muted small text-uppercase pb-3">Status</th>
                                <th class="text-muted small text-uppercase pb-3">Value</th>
                                <th class="text-muted small text-uppercase pb-3">Assigned To</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($highValueDeals as $client)
                            <tr style="border-bottom: 1px solid var(--card-border);">
                                <td class="px-4 py-3 fw-medium text-dark">{{ $client->company_name ?? 'N/A' }}</td>
                                <td class="py-3 text-dark">{{ $client->client_name }}</td>
                                <td class="py-3">
                                    @php
                                        $services = is_string($client->finalized_services) ? json_decode($client->finalized_services, true) : $client->finalized_services;
                                        $mainService = is_array($services) && count($services) > 0 ? $services[0] : 'Consulting';
                                    @endphp
                                    <span class="badge bg-secondary bg-opacity-10 text-dark fw-normal rounded-pill px-3">{{ $mainService }}</span>
                                </td>
                                <td class="py-3">
                                    @php
                                        $statusConfig = match($client->verification_status) {
                                            'pending' => ['bg' => 'rgba(59, 130, 246, 0.1)', 'color' => '#3B82F6', 'label' => 'Negotiation'],
                                            'in_progress' => ['bg' => 'rgba(139, 92, 246, 0.1)', 'color' => '#8B5CF6', 'label' => 'In Progress'],
                                            'completed' => ['bg' => 'rgba(16, 185, 129, 0.1)', 'color' => '#10B981', 'label' => 'Won'],
                                            default => ['bg' => 'rgba(100, 116, 139, 0.1)', 'color' => '#94A3B8', 'label' => ucfirst($client->verification_status)]
                                        };
                                    @endphp
                                    <span class="badge rounded-pill px-3 fw-medium" style="background-color: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['color'] }};">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>
                                <td class="py-3 fw-bold text-dark">${{ number_format($client->deal_amount) }}</td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        @if($client->lead && $client->lead->assignee)
                                            <div class="avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background: linear-gradient(135deg, #0284c7, #38bdf8); width: 24px; height: 24px; font-size: 10px;">
                                                {{ strtoupper(substr($client->lead->assignee->name, 0, 1)) }}
                                            </div>
                                            <span class="text-dark small">{{ $client->lead->assignee->name }}</span>
                                        @else
                                            <span class="text-muted small">Unassigned</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No high-value deals found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Staff Workload -->
    <div class="col-lg-4">
        <div class="card h-100 border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
            <div class="card-header border-0 pb-0 pt-4 px-4">
                <h5 class="mb-0 fw-bold text-dark">Staff Workload</h5>
            </div>
            <div class="card-body px-4 pt-3 pb-4">
                @foreach($staffWorkload as $staff)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center me-3 text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: linear-gradient(135deg, #0284c7, #38bdf8);">
                            {{ strtoupper(substr($staff->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark mb-0" style="line-height: 1.2;">{{ $staff->name }}</div>
                            <small class="text-muted">{{ ucfirst($staff->role) }} Dept.</small>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3 py-2 fw-medium border border-secondary border-opacity-25">
                            {{ $staff->assigned_leads_count }} Leads
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
