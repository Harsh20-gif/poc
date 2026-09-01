@extends('layouts.app')
@section('title', 'Clients Directory')
@section('subtitle', 'Manage all certified clients.')

@section('topbar_actions')
    <button class="btn btn-outline-secondary text-dark border-secondary me-2" onclick="window.location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
    </button>
    <a href="{{ route('leads.create') }}" class="btn text-white border-0 shadow-sm me-2" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
        <i class="bi bi-plus-lg me-1"></i> New Lead
    </a>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-outline-secondary text-dark border-secondary"><i class="bi bi-person-plus"></i> Add Staff</a>
@endsection

@section('content')

@include('layouts.partials.stat_cards')

<div class="card mb-4 border-0" style="background-color: var(--card-bg); border: 1px solid var(--card-border) !important;">
    <div class="card-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">Certified CRM Clients</h5>
    </div>
    <div class="card-body px-4 pt-3 pb-4">
        <form method="GET" action="{{ route('clients.index') }}" class="row g-3 align-items-center mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control text-dark border-secondary rounded-pill" placeholder="Search Company or Client Name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary rounded-pill w-100">Search</button>
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
                        <th class="text-muted small text-uppercase pb-3">Service Type</th>
                        <th class="text-muted small text-uppercase pb-3">Converted Date</th>
                        <th class="text-muted small text-uppercase pb-3">Assigned Staff</th>
                        <th class="text-muted small text-uppercase pb-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr style="border-bottom: 1px solid var(--card-border);">
                        <td class="py-3 fw-medium text-dark">{{ $client->company_name ?? '—' }}</td>
                        <td class="py-3 text-dark">{{ $client->client_name }}</td>
                        <td class="py-3">
                            <div class="fw-bold text-dark">{{ $client->lead?->mobile ?? '—' }}</div>
                            <div class="text-muted small">{{ $client->lead?->email ?? '—' }}</div>
                        </td>
                        <td class="py-3 text-dark">{{ $client->lead?->city ?? '—' }}</td>
                        <td class="py-3">
                            @php
                                $services = is_string($client->finalized_services) ? json_decode($client->finalized_services, true) : $client->finalized_services;
                            @endphp
                            @if(is_array($services) && count($services) > 0)
                                <span class="badge bg-secondary bg-opacity-10 text-dark fw-normal rounded-pill px-3">{{ $services[0] }}</span>
                                @if(count($services) > 1)
                                    <span class="badge bg-secondary bg-opacity-10 text-muted fw-normal rounded-pill ms-1">+{{ count($services) - 1 }} more</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-dark">
                            @if($client->conversion_date)
                                {{ \Carbon\Carbon::parse($client->conversion_date)->format('M d, Y') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($client->lead?->assignee)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background: linear-gradient(135deg, #0284c7, #38bdf8); width: 24px; height: 24px; font-size: 10px;">
                                        {{ strtoupper(substr($client->lead->assignee->name, 0, 1)) }}
                                    </div>
                                    <span class="text-dark small">{{ $client->lead->assignee->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-end">
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-secondary text-dark border-secondary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No converted clients yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $clients->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
