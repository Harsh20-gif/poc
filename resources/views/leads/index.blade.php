@extends('layouts.app')
@section('title', 'Leads Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Leads</h4>
    </div>
    <div>
        <a href="{{ route('leads.create') }}" class="btn btn-primary me-2"><i class="bi bi-plus-lg"></i> New Lead</a>
        <a href="{{ route('leads.import.form') }}" class="btn btn-outline-secondary"><i class="bi bi-upload"></i> Bulk Import</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-light">
        <form method="GET" action="{{ route('leads.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name, Email, Mobile..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Active (Not Converted)</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === (string)$key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Source</label>
                <select name="source" class="form-select">
                    <option value="">All Sources</option>
                    @foreach($sources as $src)
                        <option value="{{ $src }}" {{ request('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" name="show_deactivated" value="1" id="show_deactivated" {{ request('show_deactivated') ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_deactivated">
                        Show Deactivated
                    </label>
                </div>
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Company / Contact</th>
                    <th>Contact Info</th>
                    <th>Source</th>
                    <th>Services</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr class="{{ !$lead->is_active ? 'table-secondary opacity-75' : '' }}">
                    <td>
                        <div class="fw-bold">{{ $lead->company_name ?? 'N/A' }}</div>
                        <div class="text-muted small">{{ $lead->contact_person }}</div>
                    </td>
                    <td>
                        <div>{{ $lead->mobile }}</div>
                        @if($lead->email) <div class="text-muted small">{{ $lead->email }}</div> @endif
                    </td>
                    <td><span class="badge bg-secondary bg-opacity-25 text-dark">{{ $lead->source }}</span></td>
                    <td>
                        @if($lead->services)
                            @foreach($lead->services as $service)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle me-1">{{ $service }}</span>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeClass = match($lead->status) {
                                'pending' => 'bg-warning text-dark',
                                'in_conversation' => 'bg-info text-dark',
                                'converted' => 'bg-success',
                                'renewal' => 'bg-danger',
                                'deactivated' => 'bg-secondary',
                                default => 'bg-light text-dark'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statuses[$lead->status] ?? ucfirst($lead->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('leads.show', $lead) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No leads found matching your criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $leads->links('pagination::bootstrap-5') }}
</div>
@endsection
